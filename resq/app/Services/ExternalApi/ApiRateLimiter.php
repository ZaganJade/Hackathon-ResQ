<?php

namespace App\Services\ExternalApi;

use App\Exceptions\RateLimitException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * API Rate Limit Handler
 * Task 13.10 Implementation
 *
 * Handles rate limiting with:
 * - Retry-After header parsing
 * - Exponential backoff with jitter
 * - Queue-based retry for 429 responses
 * - Token bucket rate limiting (optional)
 */
class ApiRateLimiter
{
    protected bool $retryEnabled;
    protected int $maxWaitSeconds;
    protected float $backoffMultiplier;

    public function __construct()
    {
        $config = config('services.external_api.rate_limit');
        $this->retryEnabled = $config['retry_enabled'] ?? true;
        $this->maxWaitSeconds = $config['max_wait_seconds'] ?? 300; // 5 minutes max
        $this->backoffMultiplier = $config['backoff_multiplier'] ?? 2.0;
    }

    /**
     * Execute with rate limit awareness
     *
     * @template T
     * @param string $service Service identifier
     * @param callable(): T $callback Function to execute
     * @param int|null $maxRetries Maximum retry attempts
     * @return T
     * @throws RateLimitException
     * @throws \Throwable
     */
    public function executeWithRateLimit(
        string $service,
        callable $callback,
        ?int $maxRetries = null
    ): mixed {
        $maxRetries = $maxRetries ?? 3;
        $lastException = null;

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                return $callback();
            } catch (RateLimitException $e) {
                $lastException = $e;

                if (!$this->retryEnabled || $attempt >= $maxRetries) {
                    throw $e;
                }

                $retryAfter = $e->getRetryAfter();

                if ($retryAfter === null) {
                    // No Retry-After header, use exponential backoff
                    $retryAfter = $this->calculateBackoff($attempt);
                }

                // Don't wait longer than max
                $waitTime = min($retryAfter, $this->maxWaitSeconds);

                if ($waitTime > $this->maxWaitSeconds) {
                    Log::warning("Rate limit wait time exceeds maximum for {$service}, aborting", [
                        'wait_time' => $waitTime,
                        'max_wait' => $this->maxWaitSeconds,
                    ]);
                    throw $e;
                }

                Log::info("Rate limited for {$service}, waiting {$waitTime}s before retry (attempt {$attempt}/{$maxRetries})", [
                    'retry_after' => $retryAfter,
                    'wait_time' => $waitTime,
                ]);

                // Record rate limit hit
                $this->recordRateLimitHit($service);

                sleep($waitTime);
            } catch (\Throwable $e) {
                // Not a rate limit error, rethrow
                throw $e;
            }
        }

        // Should not reach here, but just in case
        if ($lastException) {
            throw $lastException;
        }

        throw new \RuntimeException('Unexpected state in rate limiter');
    }

    /**
     * Check if we're currently rate limited for a service
     */
    public function isRateLimited(string $service): bool
    {
        $retryAfter = Cache::get("rate_limit:{$service}:retry_after");

        if (!$retryAfter) {
            return false;
        }

        return now()->isBefore($retryAfter);
    }

    /**
     * Set rate limit for a service (e.g., from API response)
     */
    public function setRateLimit(string $service, int $retryAfterSeconds): void
    {
        $retryAfter = now()->addSeconds($retryAfterSeconds);
        Cache::put("rate_limit:{$service}:retry_after", $retryAfter, $retryAfterSeconds + 60);

        Log::info("Rate limit set for {$service}, retry after {$retryAfterSeconds}s", [
            'retry_after' => $retryAfter->toIso8601String(),
        ]);
    }

    /**
     * Clear rate limit for a service
     */
    public function clearRateLimit(string $service): void
    {
        Cache::forget("rate_limit:{$service}:retry_after");
    }

    /**
     * Get current rate limit status
     */
    public function getStatus(string $service): array
    {
        $retryAfter = Cache::get("rate_limit:{$service}:retry_after");
        $hits = Cache::get("rate_limit:{$service}:hits", 0);

        $remaining = 0;
        if ($retryAfter) {
            $remaining = max(0, now()->diffInSeconds($retryAfter));
        }

        return [
            'service' => $service,
            'is_limited' => $this->isRateLimited($service),
            'retry_after_seconds' => $remaining,
            'total_hits_24h' => $hits,
        ];
    }

    /**
     * Calculate backoff delay with jitter
     */
    protected function calculateBackoff(int $attempt): int
    {
        // Exponential backoff: 1s, 2s, 4s, 8s...
        $baseDelay = pow($this->backoffMultiplier, $attempt - 1);

        // Add jitter (±25%) to prevent thundering herd
        $jitter = $baseDelay * (random_int(-25, 25) / 100);
        $delay = $baseDelay + $jitter;

        return (int) max(1, $delay);
    }

    /**
     * Record rate limit hit for monitoring
     */
    protected function recordRateLimitHit(string $service): void
    {
        $key = "rate_limit:{$service}:hits";
        Cache::increment($key);
        Cache::expire($key, 86400); // 24 hours

        // Track in sliding window
        $windowKey = "rate_limit:{$service}:window";
        $hits = Cache::get($windowKey, []);
        $hits[] = now()->getTimestamp();

        // Keep only last hour
        $cutoff = now()->subHour()->getTimestamp();
        $hits = array_filter($hits, fn($t) => $t > $cutoff);

        Cache::put($windowKey, $hits, 3600);
    }

    /**
     * Get rate limit hits in time window
     */
    public function getRecentHits(string $service, int $minutes = 60): int
    {
        $key = "rate_limit:{$service}:window";
        $hits = Cache::get($key, []);

        $cutoff = now()->subMinutes($minutes)->getTimestamp();
        return count(array_filter($hits, fn($t) => $t > $cutoff));
    }

    /**
     * Implement token bucket rate limiting
     * Returns true if request allowed, false if rate limited
     */
    public function checkTokenBucket(string $service, int $maxTokens = 100, int $refillRate = 10): bool
    {
        $bucketKey = "token_bucket:{$service}";
        $lastRefillKey = "token_bucket:{$service}:last_refill";

        $now = now();
        $bucket = Cache::get($bucketKey, $maxTokens);
        $lastRefill = Cache::get($lastRefillKey, $now);

        // Calculate tokens to add based on time passed
        $secondsPassed = $now->diffInSeconds($lastRefill);
        $tokensToAdd = $secondsPassed * $refillRate;

        // Refill bucket
        $bucket = min($maxTokens, $bucket + $tokensToAdd);

        // Check if we can consume a token
        if ($bucket >= 1) {
            $bucket--;
            Cache::put($bucketKey, $bucket, 3600);
            Cache::put($lastRefillKey, $now, 3600);
            return true;
        }

        // Rate limited
        Cache::put($bucketKey, $bucket, 3600);
        Cache::put($lastRefillKey, $lastRefill, 3600);

        return false;
    }

    /**
     * Queue request for later processing when rate limited
     */
    public function queueForRetry(string $service, array $payload, int $delaySeconds): void
    {
        $queueKey = "rate_limit_queue:{$service}";
        $queue = Cache::get($queueKey, []);

        $queue[] = [
            'payload' => $payload,
            'retry_at' => now()->addSeconds($delaySeconds)->getTimestamp(),
            'attempts' => 0,
        ];

        Cache::put($queueKey, $queue, $delaySeconds + 3600);

        Log::info("Request queued for {$service} due to rate limiting", [
            'delay_seconds' => $delaySeconds,
            'queue_size' => count($queue),
        ]);
    }

    /**
     * Process queued requests that are ready
     */
    public function processQueue(string $service, callable $processor): array
    {
        $queueKey = "rate_limit_queue:{$service}";
        $queue = Cache::get($queueKey, []);

        $processed = [];
        $remaining = [];
        $now = now()->getTimestamp();

        foreach ($queue as $item) {
            if ($item['retry_at'] <= $now) {
                try {
                    $processor($item['payload']);
                    $processed[] = $item;
                } catch (\Throwable $e) {
                    Log::warning("Failed to process queued request for {$service}", [
                        'error' => $e->getMessage(),
                    ]);

                    // Re-queue if attempts < 3
                    if ($item['attempts'] < 3) {
                        $item['attempts']++;
                        $item['retry_at'] = now()->addMinutes(5 * $item['attempts'])->getTimestamp();
                        $remaining[] = $item;
                    }
                }
            } else {
                $remaining[] = $item;
            }
        }

        Cache::put($queueKey, $remaining, 3600);

        return [
            'processed' => count($processed),
            'remaining' => count($remaining),
        ];
    }

    /**
     * Get queue size
     */
    public function getQueueSize(string $service): int
    {
        $queue = Cache::get("rate_limit_queue:{$service}", []);
        return count($queue);
    }
}
