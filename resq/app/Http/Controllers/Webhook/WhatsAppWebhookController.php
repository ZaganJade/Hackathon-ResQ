<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Jobs\SendWhatsAppNotificationJob;
use App\Models\NotificationLog;
use App\Services\ExternalApi\WhatsAppService;
use App\Services\NotificationService;
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

        // Send WhatsApp message and create notification log
        try {
            $startTime = microtime(true);
            $notificationService = app(NotificationService::class);

            // Create notification log for guest user (user_id = null)
            $log = $notificationService->createLog(
                userId: null,
                phone: $phone,
                message: $message
            );

            // Send message
            $result = $notificationService->sendMessage($phone, $message);

            // Update log status based on result
            if ($result['success']) {
                $log->markAsSent();
            } else {
                $log->markAsFailed($result['error'] ?? 'unknown_error');
            }

            $responseTime = round((microtime(true) - $startTime) * 1000, 2);

            // Log successful webhook call
            Log::info('WhatsApp webhook sent successfully', [
                'phone' => $phone,
                'message_id' => $result['message_id'] ?? null,
                'response_time_ms' => $responseTime,
                'disaster_type' => $validated['disaster_type'] ?? null,
                'ip' => $request->ip(),
                'notification_log_id' => $log->id,
            ]);

            return response()->json([
                'success' => $result['success'],
                'data' => [
                    'message_id' => $result['message_id'],
                    'phone' => $phone,
                    'status' => $result['success'] ? 'sent' : 'failed',
                    'sent_at' => now()->toIso8601String(),
                    'provider' => 'yobase',
                    'notification_log_id' => $log->id,
                ],
                'meta' => [
                    'response_time_ms' => $responseTime,
                    'timestamp' => now()->toIso8601String(),
                ],
            ], $result['success'] ? 200 : 500);

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

        // If disaster info provided, build clean message
        if (!empty($data['disaster_type'])) {
            $severityEmoji = [
                'critical' => '🚨',
                'high' => '⚠️',
                'medium' => '⚡',
                'low' => 'ℹ️',
            ][$data['severity'] ?? 'medium'] ?? '⚠️';

            $severityText = [
                'critical' => 'KRITIS - Segera evakuasi!',
                'high' => 'TINGGI - Waspada & siap evakuasi',
                'medium' => 'SEDANG - Pantau perkembangan',
                'low' => 'RENDAH - Tetap waspada',
            ][$data['severity'] ?? 'medium'] ?? 'Waspada';

            $typeTranslations = [
                'earthquake' => 'GEMPA BUMI',
                'flood' => 'BANJIR',
                'tsunami' => 'TSUNAMI',
                'landslide' => 'TANAH LONGSOR',
                'volcanic_eruption' => 'LETUSAN GUNUNG BERAPI',
                'fire' => 'KEBAKARAN',
                'tornado' => 'PUTING BELIUNG',
            ];

            $typeId = $typeTranslations[$data['disaster_type'] ?? ''] ?? strtoupper($data['disaster_type'] ?? 'BENCANA');

            // Use location from message if coords, or from data
            $location = $data['location'] ?? '';
            if (preg_match('/^-?\d+\.\d+/', $location)) {
                // If location is coordinates, extract readable location from message
                preg_match('/di\s+(.+)!?$/', $message, $matches);
                $location = $matches[1] ?? $location;
            }

            // Extract magnitude from message
            preg_match('/magnitude\s+([\d.]+)/i', $message, $magMatch);
            $magnitude = $magMatch[1] ?? null;

            $magLine = $magnitude ? "🔢 *Magnitudo: M{$magnitude}*\n" : '';

            return <<<MSG
{$severityEmoji} *PERINGATAN {$typeId}*

📍 *Lokasi:* {$location}
{$magLine}⚠️ *Status:* {$severityText}

🛡️ *Tips Keselamatan:*
• Tetap tenang, jangan panik
• Cari tempat berlindung yang aman
• Jauhi jendela, kaca, dan benda berat
• Ikuti arahan dari petugas darurat

📞 Darurat: 119 | 🌐 resq.id
MSG;
        }

        return $message;
    }

    /**
     * Send WhatsApp notification to ALL registered users (broadcast)
     *
     * POST /api/v1/webhook/whatsapp/broadcast
     *
     * Headers:
     * - X-API-Key: your_webhook_api_key
     * - Content-Type: application/json
     *
     * Body:
     * {
     *   "message": "Peringatan gempa magnitude 6.0 di Jakarta!",
     *   "disaster_type": "earthquake",
     *   "location": "Jakarta",
     *   "severity": "high",
     *   "filter_type": "earthquake" // optional: filter by disaster type
     * }
     */
    public function broadcast(Request $request): JsonResponse
    {
        // Rate limiting: max 20 broadcast requests per hour per API key
        $apiKey = $request->header('X-API-Key');
        $rateLimitKey = 'webhook:whatsapp:broadcast:' . ($apiKey ?? 'unknown');

        if (RateLimiter::tooManyAttempts($rateLimitKey, 20)) {
            return response()->json([
                'success' => false,
                'error' => 'Broadcast rate limit exceeded. Max 20 broadcasts per hour.',
                'retry_after' => RateLimiter::availableIn($rateLimitKey),
            ], 429);
        }

        RateLimiter::hit($rateLimitKey, 3600); // 1 hour window

        // Authenticate webhook request
        if (!$this->authenticate($request)) {
            Log::warning('Unauthorized broadcast attempt', [
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
                'message' => 'required|string|min:1|max:2000',
                'disaster_type' => 'nullable|string|max:50',
                'location' => 'nullable|string|max:200',
                'severity' => 'nullable|string|in:low,medium,high,critical',
                'filter_type' => 'nullable|string|max:50', // Filter by subscribed disaster type
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'details' => $e->errors(),
            ], 422);
        }

        // Get all active notification preferences
        $query = \App\Models\NotificationPreference::active();

        // Filter by disaster type if specified
        if (!empty($validated['filter_type'])) {
            $disasterType = $validated['filter_type'];
            $preferences = $query->get()->filter(function ($pref) use ($disasterType) {
                return $pref->isSubscribedTo($disasterType);
            });
        } else {
            $preferences = $query->get();
        }

        if ($preferences->isEmpty()) {
            return response()->json([
                'success' => false,
                'error' => 'No active subscribers found.',
            ], 404);
        }

        // Allow ALL severity levels for notifications (low, medium, high, critical)
        $severity = $validated['severity'] ?? 'medium';

        // Build message
        $message = $this->buildMessage($validated);

        // Send to all users
        $results = [
            'total' => $preferences->count(),
            'sent' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        $startTime = microtime(true);
        $notificationService = app(NotificationService::class);

        foreach ($preferences as $preference) {
            try {
                $phone = $this->whatsAppService->normalizePhoneNumber($preference->whatsapp_number);

                // Validate phone
                if (!$this->whatsAppService->validatePhoneNumber($phone)) {
                    $results['errors'][] = [
                        'phone' => $preference->whatsapp_number,
                        'error' => 'Invalid phone number',
                    ];
                    $results['failed']++;
                    continue;
                }

                // Create notification log and dispatch job
                $log = $notificationService->createLog(
                    $preference->user_id,
                    $phone,
                    $message
                );
                SendWhatsAppNotificationJob::dispatch($log);
                $results['sent']++;

                // Small delay to avoid rate limiting (jobs are dispatched to queue)
                usleep(50000); // 50ms delay between dispatches

            } catch (\Throwable $e) {
                $results['errors'][] = [
                    'phone' => $preference->whatsapp_number,
                    'error' => $e->getMessage(),
                ];
                $results['failed']++;

                Log::error('Broadcast failed for user', [
                    'user_id' => $preference->user_id,
                    'phone' => $preference->whatsapp_number,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $totalTime = round((microtime(true) - $startTime) * 1000, 2);

        // Log broadcast
        Log::info('WhatsApp broadcast completed', [
            'total' => $results['total'],
            'sent' => $results['sent'],
            'failed' => $results['failed'],
            'total_time_ms' => $totalTime,
            'ip' => $request->ip(),
        ]);

        return response()->json([
            'success' => $results['sent'] > 0,
            'data' => [
                'total_subscribers' => $results['total'],
                'sent' => $results['sent'],
                'failed' => $results['failed'],
                'success_rate' => $results['total'] > 0 ? round(($results['sent'] / $results['total']) * 100, 2) : 0,
            ],
            'errors' => $results['errors'] ?: null,
            'meta' => [
                'total_time_ms' => $totalTime,
                'timestamp' => now()->toIso8601String(),
            ],
        ], $results['sent'] > 0 ? 200 : 500);
    }
}
