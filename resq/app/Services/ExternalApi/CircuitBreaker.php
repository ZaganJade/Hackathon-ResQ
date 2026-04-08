<?php

namespace App\Services\ExternalApi;

use App\Exceptions\CircuitOpenException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Circuit Breaker Pattern Implementation
 * Task 13.6 Implementation
 *
 * Prevents cascade failures when external APIs are down by:
 * - Tracking failure counts
 * - Opening circuit when threshold reached
 * - Half-open state to test recovery
 * - Automatic reset after timeout
 *
 * States:
 * - CLOSED: Normal operation, requests pass through
 * - OPEN: Failure threshold reached, requests fail fast
 * - HALF_OPEN: Testing if service recovered
 */
class CircuitBreaker
{
    const STATE_CLOSED = 'closed';
    const STATE_OPEN = 'open';
    const STATE_HALF_OPEN = 'half_open';

    protected int $failureThreshold;
    protected int $timeout; // seconds
    protected int $halfOpenRequests;

    public function __construct()
    {
        $config = config('services.external_api.circuit_breaker');
        $this->failureThreshold = $config['failure_threshold'] ?? 5;
        $this->timeout = $config['timeout'] ?? 60;
        $this->halfOpenRequests = $config['half_open_requests'] ?? 3;
    }

    /**
     * Execute callable with circuit breaker protection
     *
     * @template T
     * @param string $service Unique service identifier
     * @param callable(): T $callback Function to execute
     * @return T
     * @throws CircuitOpenException
     * @throws \Throwable
     */
    public function call(string $service, callable $callback): mixed
    {
        $state = $this->getState($service);

        // Check if circuit is open
        if ($state === self::STATE_OPEN) {
            if ($this->shouldAttemptReset($service)) {
                $state = self::STATE_HALF_OPEN;
                $this->setState($service, $state);
                Log::info("Circuit breaker for {$service} entering HALF_OPEN state");
            } else {
                $retryAfter = $this->getRemainingTimeout($service);
                Log::warning("Circuit breaker OPEN for {$service}, request blocked", [
                    'retry_after_seconds' => $retryAfter,
                ]);
                throw new CircuitOpenException($service, $retryAfter);
            }
        }

        // Execute request
        try {
            $result = $callback();
            $this->recordSuccess($service);
            return $result;
        } catch (\Throwable $e) {
            $this->recordFailure($service);
            throw $e;
        }
    }

    /**
     * Check if a service circuit is currently open
     */
    public function isOpen(string $service): bool
    {
        return $this->getState($service) === self::STATE_OPEN;
    }

    /**
     * Force open a circuit (for maintenance or known outages)
     */
    public function forceOpen(string $service, ?int $duration = null): void
    {
        $this->setState($service, self::STATE_OPEN);
        $openTime = now();
        Cache::put($this->key($service, 'opened_at'), $openTime, $duration ?? $this->timeout);

        Log::info("Circuit breaker for {$service} forcefully opened", [
            'duration' => $duration ?? $this->timeout,
        ]);
    }

    /**
     * Force close a circuit (manual reset)
     */
    public function forceClose(string $service): void
    {
        $this->reset($service);
        Log::info("Circuit breaker for {$service} forcefully closed");
    }

    /**
     * Get current circuit state with metrics
     */
    public function getStatus(string $service): array
    {
        $state = $this->getState($service);
        $failures = Cache::get($this->key($service, 'failures'), 0);
        $successes = Cache::get($this->key($service, 'successes'), 0);
        $openedAt = Cache::get($this->key($service, 'opened_at'));

        return [
            'service' => $service,
            'state' => $state,
            'failure_count' => $failures,
            'success_count' => $successes,
            'failure_threshold' => $this->failureThreshold,
            'timeout' => $this->timeout,
            'opened_at' => $openedAt?->toIso8601String(),
            'retry_after' => $state === self::STATE_OPEN ? $this->getRemainingTimeout($service) : 0,
        ];
    }

    /**
     * Get status for all services
     */
    public function getAllStatus(): array
    {
        // Get all cached circuit keys
        $keys = Cache::get('circuit_breaker_services', []);
        $status = [];

        foreach ($keys as $service) {
            $status[$service] = $this->getStatus($service);
        }

        return $status;
    }

    /**
     * Reset circuit breaker for a service
     */
    public function reset(string $service): void
    {
        Cache::forget($this->key($service, 'state'));
        Cache::forget($this->key($service, 'failures'));
        Cache::forget($this->key($service, 'successes'));
        Cache::forget($this->key($service, 'opened_at'));
        Cache::forget($this->key($service, 'half_open_requests'));

        // Remove from services list
        $services = Cache::get('circuit_breaker_services', []);
        $services = array_diff($services, [$service]);
        Cache::put('circuit_breaker_services', $services, now()->addDays(1));
    }

    /**
     * Record successful request
     */
    protected function recordSuccess(string $service): void
    {
        $state = $this->getState($service);

        if ($state === self::STATE_HALF_OPEN) {
            $halfOpenCount = Cache::increment($this->key($service, 'half_open_requests'));

            if ($halfOpenCount >= $this->halfOpenRequests) {
                // Service appears healthy, close circuit
                $this->reset($service);
                Log::info("Circuit breaker for {$service} CLOSED (recovered)");
            }
        }

        // Track successes for monitoring
        Cache::increment($this->key($service, 'successes'));
        $this->trackService($service);
    }

    /**
     * Record failed request
     */
    protected function recordFailure(string $service): void
    {
        $state = $this->getState($service);
        $failures = Cache::increment($this->key($service, 'failures'));

        if ($state === self::STATE_HALF_OPEN) {
            // Failed in half-open state, reopen circuit
            $this->openCircuit($service);
            return;
        }

        if ($failures >= $this->failureThreshold) {
            $this->openCircuit($service);
        }

        $this->trackService($service);
    }

    /**
     * Open the circuit
     */
    protected function openCircuit(string $service): void
    {
        $this->setState($service, self::STATE_OPEN);
        Cache::put($this->key($service, 'opened_at'), now(), $this->timeout * 2);

        Log::error("Circuit breaker OPENED for {$service}", [
            'failure_threshold' => $this->failureThreshold,
            'timeout' => $this->timeout,
        ]);
    }

    /**
     * Get current state
     */
    protected function getState(string $service): string
    {
        return Cache::get($this->key($service, 'state'), self::STATE_CLOSED);
    }

    /**
     * Set state
     */
    protected function setState(string $service, string $state): void
    {
        Cache::put($this->key($service, 'state'), $state, $this->timeout * 2);
        $this->trackService($service);
    }

    /**
     * Check if enough time has passed to attempt reset
     */
    protected function shouldAttemptReset(string $service): bool
    {
        $openedAt = Cache::get($this->key($service, 'opened_at'));

        if (!$openedAt) {
            return true;
        }

        return now()->diffInSeconds($openedAt) >= $this->timeout;
    }

    /**
     * Get remaining timeout seconds
     */
    protected function getRemainingTimeout(string $service): int
    {
        $openedAt = Cache::get($this->key($service, 'opened_at'));

        if (!$openedAt) {
            return 0;
        }

        $elapsed = now()->diffInSeconds($openedAt);
        $remaining = max(0, $this->timeout - $elapsed);

        return (int) $remaining;
    }

    /**
     * Generate cache key
     */
    protected function key(string $service, string $type): string
    {
        return "circuit:{$service}:{$type}";
    }

    /**
     * Track service in registry
     */
    protected function trackService(string $service): void
    {
        $services = Cache::get('circuit_breaker_services', []);

        if (!in_array($service, $services)) {
            $services[] = $service;
            Cache::put('circuit_breaker_services', $services, now()->addDays(1));
        }
    }
}
