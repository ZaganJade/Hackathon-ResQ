<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Exception for External API failures
 * Used by Task 13.2, 13.8 for error handling
 */
class ApiException extends Exception
{
    protected ?string $service;
    protected ?array $context;
    protected ?int $statusCode;

    public function __construct(
        string $message = 'API request failed',
        int $code = 0,
        ?Exception $previous = null,
        ?string $service = null,
        ?array $context = [],
        ?int $statusCode = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->service = $service;
        $this->context = $context;
        $this->statusCode = $statusCode;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function getContext(): array
    {
        return $this->context ?? [];
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    /**
     * Log the exception with structured context
     */
    public function log(): void
    {
        Log::error('External API Error', [
            'service' => $this->service,
            'message' => $this->getMessage(),
            'code' => $this->code,
            'status_code' => $this->statusCode,
            'context' => $this->context,
            'trace' => $this->getTraceAsString(),
        ]);
    }

    /**
     * Check if error is retryable
     */
    public function isRetryable(): bool
    {
        // Retry on network errors (code 0) or 5xx server errors
        if ($this->code === 0) {
            return true;
        }

        if ($this->statusCode !== null && $this->statusCode >= 500 && $this->statusCode < 600) {
            return true;
        }

        // Retry on 429 rate limit
        if ($this->statusCode === 429) {
            return true;
        }

        // Retry on timeout
        if ($this->statusCode === 408) {
            return true;
        }

        return false;
    }
}

/**
 * Circuit breaker is open - service temporarily unavailable
 * Task 13.6
 */
class CircuitOpenException extends ApiException
{
    public function __construct(
        string $service,
        ?int $retryAfter = null
    ) {
        $message = "Service '{$service}' is temporarily unavailable (circuit breaker open)";
        if ($retryAfter) {
            $message .= ". Retry after {$retryAfter} seconds.";
        }

        parent::__construct(
            message: $message,
            code: 503,
            service: $service,
            context: ['retry_after' => $retryAfter]
        );
    }

    public function isRetryable(): bool
    {
        return true; // Can retry after circuit closes
    }
}

/**
 * Rate limit exceeded
 * Task 13.10
 */
class RateLimitException extends ApiException
{
    protected ?int $retryAfter;

    public function __construct(
        string $service,
        ?int $retryAfter = null
    ) {
        $message = "Rate limit exceeded for service '{$service}'";

        parent::__construct(
            message: $message,
            code: 429,
            statusCode: 429,
            service: $service,
            context: ['retry_after' => $retryAfter]
        );

        $this->retryAfter = $retryAfter;
    }

    public function getRetryAfter(): ?int
    {
        return $this->retryAfter;
    }

    public function isRetryable(): bool
    {
        return true;
    }
}

/**
 * Fallback data unavailable
 * Task 13.8
 */
class FallbackUnavailableException extends ApiException
{
    public function __construct(string $service)
    {
        parent::__construct(
            message: "Service '{$service}' failed and no fallback data available",
            code: 503,
            service: $service
        );
    }

    public function isRetryable(): bool
    {
        return false;
    }
}
