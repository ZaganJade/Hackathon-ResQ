<?php

namespace App\Services\ExternalApi;

use App\Exceptions\ApiException;
use App\Exceptions\RateLimitException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Base API Client with Timeout and Retry Logic
 * Task 13.2 Implementation
 *
 * Provides:
 * - Configurable timeout for API requests
 * - Exponential backoff retry mechanism
 * - Connection error handling
 * - Request/response logging
 * - Rate limit detection
 */
abstract class BaseApiClient
{
    protected string $baseUrl;
    protected int $timeout;
    protected int $maxRetries;
    protected int $retryDelay; // milliseconds
    protected float $retryMultiplier;
    protected array $headers = [];
    protected ?string $apiKey = null;

    public function __construct()
    {
        $this->configure();
    }

    /**
     * Configure client settings from config/services.php
     * Must be implemented by child classes
     */
    abstract protected function configure(): void;

    /**
     * Get service name for logging and circuit breaker
     */
    abstract protected function getServiceName(): string;

    /**
     * Create HTTP client with base configuration
     */
    protected function createClient(): PendingRequest
    {
        $client = Http::timeout($this->timeout)
            ->connectTimeout(5)
            ->retry($this->maxRetries, $this->retryDelay, function ($exception, $request) {
                // Only retry on connection errors or 5xx responses
                if ($exception instanceof ConnectionException) {
                    Log::warning("Connection error for {$this->getServiceName()}, retrying...", [
                        'url' => $request->url(),
                    ]);
                    return true;
                }
                return false;
            });

        if (!empty($this->headers)) {
            $client = $client->withHeaders($this->headers);
        }

        if ($this->apiKey) {
            $client = $this->authenticate($client);
        }

        return $client;
    }

    /**
     * Authenticate the HTTP client
     * Can be overridden by child classes for different auth methods
     */
    protected function authenticate(PendingRequest $client): PendingRequest
    {
        return $client->withToken($this->apiKey);
    }

    /**
     * Make a GET request with retry logic
     *
     * @throws ApiException
     */
    protected function get(string $endpoint, array $query = []): array
    {
        return $this->request('get', $endpoint, $query);
    }

    /**
     * Make a POST request with retry logic
     *
     * @throws ApiException
     */
    protected function post(string $endpoint, array $data = []): array
    {
        return $this->request('post', $endpoint, $data);
    }

    /**
     * Make a PUT request with retry logic
     *
     * @throws ApiException
     */
    protected function put(string $endpoint, array $data = []): array
    {
        return $this->request('put', $endpoint, $data);
    }

    /**
     * Make a DELETE request with retry logic
     *
     * @throws ApiException
     */
    protected function delete(string $endpoint, array $query = []): array
    {
        return $this->request('delete', $endpoint, $query);
    }

    /**
     * Execute HTTP request with manual retry logic for specific error codes
     *
     * @throws ApiException
     */
    protected function request(string $method, string $endpoint, array $payload = []): array
    {
        $url = $this->buildUrl($endpoint);
        $lastException = null;

        for ($attempt = 1; $attempt <= $this->maxRetries; $attempt++) {
            $startTime = microtime(true);

            try {
                $client = $this->createClient();
                $response = match ($method) {
                    'get' => $client->get($url, $payload),
                    'post' => $client->post($url, $payload),
                    'put' => $client->put($url, $payload),
                    'delete' => $client->delete($url, $payload),
                    default => throw new ApiException("Invalid HTTP method: {$method}", 0, null, $this->getServiceName()),
                };

                $responseTime = round((microtime(true) - $startTime) * 1000, 2);

                // Handle rate limiting (Task 13.10)
                if ($response->status() === 429) {
                    $retryAfter = $this->extractRetryAfter($response);

                    if ($attempt < $this->maxRetries) {
                        $delay = $retryAfter ?? $this->calculateBackoff($attempt);
                        Log::warning("Rate limited for {$this->getServiceName()}, waiting {$delay}s before retry", [
                            'attempt' => $attempt,
                            'retry_after' => $retryAfter,
                        ]);
                        sleep($delay);
                        continue;
                    }

                    throw new RateLimitException($this->getServiceName(), $retryAfter);
                }

                // Handle server errors - retry if possible
                if ($response->serverError()) {
                    if ($attempt < $this->maxRetries) {
                        $delay = $this->calculateBackoff($attempt);
                        Log::warning("Server error {$response->status()} for {$this->getServiceName()}, retrying in {$delay}s", [
                            'attempt' => $attempt,
                            'status' => $response->status(),
                        ]);
                        sleep($delay);
                        continue;
                    }

                    throw new ApiException(
                        message: "Server error: {$response->status()}",
                        code: 0,
                        statusCode: $response->status(),
                        service: $this->getServiceName(),
                        context: ['response' => $response->body(), 'url' => $url]
                    );
                }

                // Handle client errors - don't retry
                if ($response->clientError()) {
                    throw new ApiException(
                        message: "Client error: {$response->status()} - {$response->body()}",
                        code: 0,
                        statusCode: $response->status(),
                        service: $this->getServiceName(),
                        context: ['response' => $response->body(), 'url' => $url, 'payload' => $payload]
                    );
                }

                $result = $response->json() ?? [];

                // Log successful request for monitoring (Task 13.9)
                $this->logSuccess($method, $url, $responseTime, $result);

                return $result;

            } catch (ConnectionException $e) {
                $lastException = $e;

                if ($attempt < $this->maxRetries) {
                    $delay = $this->calculateBackoff($attempt);
                    Log::warning("Connection failed for {$this->getServiceName()}, retrying in {$delay}s (attempt {$attempt}/{$this->maxRetries})", [
                        'error' => $e->getMessage(),
                        'url' => $url,
                    ]);
                    sleep($delay);
                }
            }
        }

        // Max retries exceeded
        throw new ApiException(
            message: "Max retries ({$this->maxRetries}) exceeded for {$this->getServiceName()}",
            code: 0,
            previous: $lastException,
            service: $this->getServiceName(),
            context: ['url' => $url, 'method' => $method]
        );
    }

    /**
     * Build full URL from endpoint
     */
    protected function buildUrl(string $endpoint): string
    {
        return rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');
    }

    /**
     * Calculate exponential backoff delay
     */
    protected function calculateBackoff(int $attempt): int
    {
        $delay = $this->retryDelay * pow($this->retryMultiplier, $attempt - 1);
        // Add jitter to prevent thundering herd
        $jitter = random_int(-100, 100);
        return (int) max(1, ($delay + $jitter) / 1000);
    }

    /**
     * Extract Retry-After header value
     */
    protected function extractRetryAfter(\Illuminate\Http\Client\Response $response): ?int
    {
        $retryAfter = $response->header('Retry-After');

        if ($retryAfter === null) {
            return null;
        }

        // Could be seconds or HTTP date
        if (is_numeric($retryAfter)) {
            return (int) $retryAfter;
        }

        return null;
    }

    /**
     * Log successful request for monitoring
     */
    protected function logSuccess(string $method, string $url, float $responseTime, array $result): void
    {
        Log::debug("API Request Success - {$this->getServiceName()}", [
            'method' => $method,
            'url' => $url,
            'response_time_ms' => $responseTime,
        ]);
    }

    /**
     * Parse error response and extract meaningful message
     */
    protected function parseErrorMessage(array $response): string
    {
        return $response['error']['message']
            ?? $response['message']
            ?? $response['error']
            ?? 'Unknown API error';
    }
}
