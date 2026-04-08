<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Services\ExternalApi\WhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

/**
 * WhatsApp Webhook Controller
 *
 * Allows 3rd party services (e.g. BMKG) to send WhatsApp notifications
 * via REST API endpoint.
 */
class WhatsAppWebhookController extends Controller
{
    private WhatsAppService $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Send WhatsApp notification via webhook
     *
     * POST /api/v1/webhook/whatsapp/send
     *
     * Headers:
     * - X-API-Key: your_webhook_api_key
     * - Content-Type: application/json
     *
     * Body:
     * {
     *   "phone": "62895341414271",
     *   "message": "Peringatan gempa!",
     *   "disaster_type": "earthquake",
     *   "location": "Jakarta",
     *   "severity": "high"
     * }
     */
    public function send(Request $request): JsonResponse
    {
        // Rate limiting: max 60 requests per minute per API key
        $apiKey = $request->header('X-API-Key');
        $rateLimitKey = 'webhook:whatsapp:' . ($apiKey ?? 'unknown');

        if (RateLimiter::tooManyAttempts($rateLimitKey, 60)) {
            return response()->json([
                'success' => false,
                'error' => 'Rate limit exceeded. Max 60 requests per minute.',
                'retry_after' => RateLimiter::availableIn($rateLimitKey),
            ], 429);
        }

        RateLimiter::hit($rateLimitKey, 60);

        // Authenticate webhook request
        if (!$this->authenticate($request)) {
            Log::warning('Unauthorized webhook attempt', [
                'ip' => $request->ip(),
                'headers' => $request->headers->all(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Unauthorized. Invalid or missing X-API-Key header.',
            ], 401);
        }

        // Validate request
        try {
            $validated = $request->validate([
                'phone' => 'required|string|min:10|max:20',
                'message' => 'required|string|min:1|max:2000',
                'disaster_type' => 'nullable|string|max:50',
                'location' => 'nullable|string|max:200',
                'severity' => 'nullable|string|in:low,medium,high,critical',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'details' => $e->errors(),
            ], 422);
        }

        // Normalize and validate phone number
        $phone = $this->whatsAppService->normalizePhoneNumber($validated['phone']);

        if (!$this->whatsAppService->validatePhoneNumber($phone)) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid phone number format. Use Indonesian format (e.g., 628123456789).',
                'phone_received' => $validated['phone'],
                'phone_normalized' => $phone,
            ], 422);
        }

        // Build message with template if disaster info provided
        $message = $this->buildMessage($validated);

        // Send WhatsApp message
        try {
            $startTime = microtime(true);

            $result = $this->whatsAppService->send($phone, $message);

            $responseTime = round((microtime(true) - $startTime) * 1000, 2);

            // Log successful webhook call
            Log::info('WhatsApp webhook sent successfully', [
                'phone' => $phone,
                'message_id' => $result['message_id'] ?? null,
                'response_time_ms' => $responseTime,
                'disaster_type' => $validated['disaster_type'] ?? null,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'message_id' => $result['message_id'],
                    'phone' => $phone,
                    'status' => $result['status'],
                    'sent_at' => $result['sent_at'],
                    'provider' => $result['provider'] ?? 'yobase',
                ],
                'meta' => [
                    'response_time_ms' => $responseTime,
                    'timestamp' => now()->toIso8601String(),
                ],
            ], 200);

        } catch (\Throwable $e) {
            Log::error('WhatsApp webhook failed', [
                'phone' => $phone,
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to send WhatsApp message',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get service status
     *
     * GET /api/v1/webhook/whatsapp/status
     */
    public function status(): JsonResponse
    {
        try {
            $accountInfo = $this->whatsAppService->getAccountInfo();

            return response()->json([
                'success' => true,
                'data' => [
                    'service' => 'whatsapp',
                    'provider' => $this->whatsAppService->getProvider(),
                    'status' => $accountInfo['status'] ?? 'unknown',
                    'account' => $accountInfo['phone'] ?? null,
                    'timestamp' => now()->toIso8601String(),
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => 'Service unavailable',
                'message' => $e->getMessage(),
            ], 503);
        }
    }

    /**
     * Authenticate webhook request
     */
    private function authenticate(Request $request): bool
    {
        $apiKey = $request->header('X-API-Key');

        if (empty($apiKey)) {
            return false;
        }

        // Get valid API keys from config
        $validKeys = config('services.webhook.api_keys', []);

        // For single key setup (backward compatibility)
        $primaryKey = config('services.webhook.api_key');
        if ($primaryKey && $apiKey === $primaryKey) {
            return true;
        }

        // For multiple keys setup
        return in_array($apiKey, $validKeys, true);
    }

    /**
     * Build message with disaster template if applicable
     */
    private function buildMessage(array $data): string
    {
        $message = $data['message'];

        // If disaster info provided, prepend with template
        if (!empty($data['disaster_type']) || !empty($data['location'])) {
            $severityEmoji = [
                'critical' => '🚨',
                'high' => '⚠️',
                'medium' => '⚡',
                'low' => 'ℹ️',
            ][$data['severity'] ?? 'medium'] ?? '⚠️';

            $typeTranslations = [
                'earthquake' => 'Gempa Bumi',
                'flood' => 'Banjir',
                'tsunami' => 'Tsunami',
                'landslide' => 'Tanah Longsor',
                'volcanic_eruption' => 'Letusan Gunung Berapi',
                'fire' => 'Kebakaran',
                'tornado' => 'Puting Beliung',
            ];

            $typeId = $typeTranslations[$data['disaster_type'] ?? ''] ?? ($data['disaster_type'] ?? 'Bencana');

            $header = "{$severityEmoji} *PERINGATAN {$typeId}*\n\n";

            if (!empty($data['location'])) {
                $header .= "📍 Lokasi: {$data['location']}\n";
            }

            if (!empty($data['severity'])) {
                $header .= "📊 Tingkat: " . ucfirst($data['severity']) . "\n";
            }

            $header .= "\n";

            $message = $header . $message;
        }

        return $message;
    }
}
