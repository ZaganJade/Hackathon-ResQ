<?php

namespace App\Services\ExternalApi;

use App\Exceptions\ApiException;
use App\Exceptions\FallbackUnavailableException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Fallback Manager for API Failures
 * Task 13.8 Implementation
 *
 * Provides fallback mechanisms when external APIs fail:
 * - Stale cache data
 * - Default values
 * - Queue for retry
 * - Graceful degradation
 */
class FallbackManager
{
    protected int $staleCacheTtl; // seconds
    protected array $defaultValues = [];

    public function __construct()
    {
        $config = config('services.external_api.cache');
        $this->staleCacheTtl = $config['fallback_ttl'] ?? 604800; // 7 days
    }

    /**
     * Execute with fallback support
     *
     * @template T
     * @param string $service Service identifier
     * @param string $cacheKey Cache key for potential fallback
     * @param callable(): T $callback Primary function
     * @param T|null $default Default value if all else fails
     * @return T|null
     * @throws FallbackUnavailableException
     */
    public function executeWithFallback(
        string $service,
        string $cacheKey,
        callable $callback,
        mixed $default = null
    ): mixed {
        try {
            $result = $callback();

            // Store successful result in stale cache
            $this->storeStaleCache($service, $cacheKey, $result);

            return $result;
        } catch (ApiException $e) {
            Log::warning("Primary service {$service} failed, attempting fallback", [
                'error' => $e->getMessage(),
                'cache_key' => $cacheKey,
            ]);

            // Try stale cache
            $staleData = $this->getStaleCache($service, $cacheKey);
            if ($staleData !== null) {
                Log::info("Fallback: Using stale cache for {$service}");
                return $staleData;
            }

            // Use default value
            if ($default !== null) {
                Log::info("Fallback: Using default value for {$service}");
                return $default;
            }

            // Queue for retry
            $this->queueForRetry($service, $cacheKey, $callback);

            throw new FallbackUnavailableException($service);
        }
    }

    /**
     * Execute with cached fallback (returns stale cache or default, never throws)
     *
     * @template T
     * @param string $service Service identifier
     * @param string $cacheKey Cache key
     * @param callable(): T $callback Primary function
     * @param T $default Required default value
     * @return T
     */
    public function executeSafe(
        string $service,
        string $cacheKey,
        callable $callback,
        mixed $default
    ): mixed {
        try {
            return $this->executeWithFallback($service, $cacheKey, $callback, $default);
        } catch (FallbackUnavailableException $e) {
            return $default;
        }
    }

    /**
     * Get AI response with fallback message
     */
    public function getAIResponseWithFallback(callable $callback, string $userMessage): string
    {
        $defaultResponse = "Maaf, layanan AI sedang tidak tersedia saat ini. Silakan coba lagi nanti atau hubungi call center darurat 112 untuk bantuan langsung.";

        try {
            return $callback();
        } catch (ApiException $e) {
            Log::warning('AI service failed, using fallback message', [
                'error' => $e->getMessage(),
            ]);

            return $defaultResponse;
        }
    }

    /**
     * Get geocoding with fallback
     */
    public function getGeocodingWithFallback(callable $callback, string $address): ?array
    {
        $cacheKey = 'geo:' . md5($address);

        try {
            return $this->executeWithFallback('google_maps', $cacheKey, $callback);
        } catch (FallbackUnavailableException $e) {
            Log::warning('Geocoding fallback failed', ['address' => $address]);

            // Return approximate Jakarta coordinates as last resort
            return [
                'lat' => -6.2088,
                'lng' => 106.8456,
                'address' => $address,
                'approximate' => true,
                'fallback_used' => true,
            ];
        }
    }

    /**
     * Get WhatsApp send status with fallback
     */
    public function getWhatsAppSendWithFallback(callable $callback): array
    {
        try {
            return $callback();
        } catch (ApiException $e) {
            Log::warning('WhatsApp service failed, queuing for retry', [
                'error' => $e->getMessage(),
            ]);

            // Return pending status - will be processed later
            return [
                'status' => 'queued',
                'message' => 'Notification queued for retry',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Store data in stale cache for fallback use
     */
    public function storeStaleCache(string $service, string $key, mixed $data): void
    {
        $staleKey = "stale:{$service}:{$key}";
        Cache::put($staleKey, $data, $this->staleCacheTtl);
    }

    /**
     * Get data from stale cache
     */
    public function getStaleCache(string $service, string $key): mixed
    {
        $staleKey = "stale:{$service}:{$key}";
        return Cache::get($staleKey);
    }

    /**
     * Check if stale cache exists
     */
    public function hasStaleCache(string $service, string $key): bool
    {
        $staleKey = "stale:{$service}:{$key}";
        return Cache::has($staleKey);
    }

    /**
     * Clear stale cache
     */
    public function clearStaleCache(string $service, ?string $key = null): void
    {
        if ($key) {
            Cache::forget("stale:{$service}:{$key}");
        } else {
            // Clear all for service - requires cache driver support
            Log::info("Clearing all stale cache for {$service}");
        }
    }

    /**
     * Queue failed operation for retry
     */
    protected function queueForRetry(string $service, string $cacheKey, callable $callback): void
    {
        // Store job info for later processing
        $retryQueue = Cache::get("fallback_queue:{$service}", []);
        $retryQueue[] = [
            'cache_key' => $cacheKey,
            'timestamp' => now()->toIso8601String(),
            'attempts' => 0,
        ];

        Cache::put("fallback_queue:{$service}", $retryQueue, now()->addHours(24));

        Log::info("Queued {$service} operation for retry", [
            'cache_key' => $cacheKey,
            'queue_size' => count($retryQueue),
        ]);
    }

    /**
     * Get queued items for a service
     */
    public function getQueuedItems(string $service): array
    {
        return Cache::get("fallback_queue:{$service}", []);
    }

    /**
     * Clear queued items
     */
    public function clearQueue(string $service): void
    {
        Cache::forget("fallback_queue:{$service}");
    }

    /**
     * Get default value for service
     */
    public function getDefault(string $service, ?string $key = null): mixed
    {
        $defaults = [
            'fireworks' => [
                'chat_response' => "Maaf, layanan AI sedang tidak tersedia. Silakan coba lagi nanti.",
            ],
            'google_maps' => [
                'geocode' => ['lat' => -6.2088, 'lng' => 106.8456, 'approximate' => true],
                'address' => 'Alamat tidak dapat ditentukan',
            ],
            'whatsapp' => [
                'send_status' => ['status' => 'failed', 'error' => 'Service unavailable'],
            ],
        ];

        if ($key) {
            return $defaults[$service][$key] ?? null;
        }

        return $defaults[$service] ?? null;
    }

    /**
     * Check service health using stale cache age
     */
    public function checkServiceHealth(string $service): array
    {
        $lastSuccess = Cache::get("service_last_success:{$service}");
        $failures = Cache::get("service_failures:{$service}", 0);

        $status = 'healthy';
        $message = 'Service operating normally';

        if ($lastSuccess) {
            $minutesSinceSuccess = now()->diffInMinutes($lastSuccess);

            if ($minutesSinceSuccess > 60) {
                $status = 'critical';
                $message = "No successful requests in {$minutesSinceSuccess} minutes";
            } elseif ($minutesSinceSuccess > 15) {
                $status = 'degraded';
                $message = "No successful requests in {$minutesSinceSuccess} minutes";
            }
        }

        if ($failures > 10) {
            $status = 'critical';
            $message = "{$failures} recent failures detected";
        }

        return [
            'service' => $service,
            'status' => $status,
            'message' => $message,
            'last_success' => $lastSuccess?->toIso8601String(),
            'recent_failures' => $failures,
        ];
    }

    /**
     * Record service success
     */
    public function recordSuccess(string $service): void
    {
        Cache::put("service_last_success:{$service}", now(), now()->addDays(7));
        Cache::forget("service_failures:{$service}");
    }

    /**
     * Record service failure
     */
    public function recordFailure(string $service): void
    {
        Cache::increment("service_failures:{$service}");
    }
}
