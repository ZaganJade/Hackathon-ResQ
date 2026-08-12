<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Webhook\Concerns\AuthenticatesWebhookRequests;
use App\Models\Disaster;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Disaster Webhook Controller
 *
 * Allows 3rd party scrapers (e.g. the BMKG Cloudflare Worker) to report
 * newly detected disasters via REST API endpoint. Persists the disaster
 * and triggers proximity-filtered WhatsApp notifications.
 */
class DisasterWebhookController extends Controller
{
    use AuthenticatesWebhookRequests;

    /**
     * Ingest a newly detected disaster
     *
     * POST /api/v1/webhook/disasters
     *
     * Headers:
     * - X-API-Key: your_webhook_api_key
     * - Content-Type: application/json
     *
     * Body:
     * {
     *   "type": "earthquake",
     *   "location": "12km Tenggara Kabupaten Jayawijaya",
     *   "latitude": -4.2,
     *   "longitude": 139.1,
     *   "magnitude": 5.5,
     *   "depth_km": 10,
     *   "occurred_at": "2026-08-11T14:52:06Z",
     *   "source_id": "bmkg_2026-08-11T14:52:06_-4.2_139.1",
     *   "raw_data": { "status": "Automatic" }
     * }
     */
    public function store(Request $request): JsonResponse
    {
        // Rate limiting: max 60 requests per minute per API key
        $apiKey = $request->header('X-API-Key');
        $rateLimitKey = 'webhook:disasters:' . ($apiKey ?? 'unknown');

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
            Log::warning('Unauthorized disaster webhook attempt', [
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Unauthorized. Invalid or missing X-API-Key header.',
            ], 401);
        }

        // Validate request
        try {
            $validated = $request->validate([
                'type' => 'required|string|in:earthquake,flood,volcano,tsunami,landslide,fire,drought,other',
                'location' => 'required|string|max:200',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'magnitude' => 'nullable|numeric',
                'depth_km' => 'nullable|numeric',
                'occurred_at' => 'nullable|date',
                'source_id' => 'required|string|max:191',
                'raw_data' => 'nullable|array',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'details' => $e->errors(),
            ], 422);
        }

        // Idempotency: ignore repeat reports of the same source event
        $disaster = Disaster::firstOrCreate(
            [
                'source' => 'bmkg_api',
                'source_id' => $validated['source_id'],
            ],
            [
                'type' => $validated['type'],
                'location' => $validated['location'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'status' => 'active',
                'raw_data' => array_merge($validated['raw_data'] ?? [], [
                    'magnitude' => $validated['magnitude'] ?? null,
                    'depth_km' => $validated['depth_km'] ?? null,
                    'occurred_at' => $validated['occurred_at'] ?? null,
                ]),
            ]
        );

        $notified = false;

        if ($disaster->wasRecentlyCreated) {
            try {
                app(NotificationService::class)->notifyForDisaster($disaster);
                $notified = true;
            } catch (\Throwable $e) {
                Log::error('Failed to notify for disaster', [
                    'disaster_id' => $disaster->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('Disaster webhook processed', [
            'disaster_id' => $disaster->id,
            'created' => $disaster->wasRecentlyCreated,
            'notified' => $notified,
            'ip' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'disaster_id' => $disaster->id,
            'created' => $disaster->wasRecentlyCreated,
            'notified' => $notified,
        ], $disaster->wasRecentlyCreated ? 201 : 200);
    }
}
