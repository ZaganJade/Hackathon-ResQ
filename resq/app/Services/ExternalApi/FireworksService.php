<?php

namespace App\Services\ExternalApi;

use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Fireworks AI Service
 * Task 13.1 Implementation
 *
 * Provides AI chat completion with:
 * - Timeout and retry logic (13.2)
 * - Circuit breaker protection (13.6)
 * - Response caching (13.7)
 * - Fallback handling (13.8)
 * - Rate limit handling (13.10)
 */
class FireworksService extends BaseApiClient
{
    protected string $model;
    protected int $maxTokens;
    protected float $temperature;
    protected ?string $systemPrompt = null;

    protected CircuitBreaker $circuitBreaker;
    protected ApiMonitor $monitor;
    protected FallbackManager $fallback;

    public function __construct(
        ?CircuitBreaker $circuitBreaker = null,
        ?ApiMonitor $monitor = null,
        ?FallbackManager $fallback = null
    ) {
        $this->circuitBreaker = $circuitBreaker ?? new CircuitBreaker();
        $this->monitor = $monitor ?? new ApiMonitor();
        $this->fallback = $fallback ?? new FallbackManager();
        parent::__construct();
    }

    protected function configure(): void
    {
        $config = config('services.fireworks');

        $this->baseUrl = 'https://api.fireworks.ai/inference/v1';
        $this->apiKey = $config['api_key'] ?? null;
        $this->timeout = $config['timeout'] ?? 30;
        $this->maxRetries = $config['max_retries'] ?? 3;
        $this->retryDelay = $config['retry_delay'] ?? 1000;
        $this->retryMultiplier = 2.0;

        $this->model = $config['model'] ?? 'accounts/fireworks/models/llama-v3p1-70b-instruct';
        $this->maxTokens = config('resq.ai_max_tokens', 1024);
        $this->temperature = config('resq.ai_temperature', 0.7);
        $this->systemPrompt = config('resq.ai_system_prompt');

        $this->headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    protected function getServiceName(): string
    {
        return 'fireworks';
    }

    /**
     * Send chat completion request
     *
     * @param array $messages Array of message objects with 'role' and 'content'
     * @param array $options Additional options (temperature, max_tokens, etc)
     * @return array Response with 'content', 'usage', etc
     * @throws ApiException
     */
    public function chat(array $messages, array $options = []): array
    {
        return $this->circuitBreaker->call($this->getServiceName(), function () use ($messages, $options) {
            $startTime = microtime(true);
            $endpoint = '/chat/completions';

            try {
                $payload = [
                    'model' => $options['model'] ?? $this->model,
                    'messages' => $messages,
                    'max_tokens' => $options['max_tokens'] ?? $this->maxTokens,
                    'temperature' => $options['temperature'] ?? $this->temperature,
                ];

                // Add system prompt if not present in messages
                if ($this->systemPrompt && !$this->hasSystemMessage($messages)) {
                    array_unshift($payload['messages'], [
                        'role' => 'system',
                        'content' => $this->systemPrompt,
                    ]);
                }

                $response = $this->post($endpoint, $payload);

                $responseTime = round((microtime(true) - $startTime) * 1000, 2);

                // Track metrics
                $this->monitor->track(
                    service: $this->getServiceName(),
                    endpoint: $endpoint,
                    responseTimeMs: $responseTime,
                    success: true,
                    statusCode: 200
                );

                // Record success for fallback manager
                $this->fallback->recordSuccess($this->getServiceName());

                return [
                    'content' => $response['choices'][0]['message']['content'] ?? '',
                    'role' => $response['choices'][0]['message']['role'] ?? 'assistant',
                    'finish_reason' => $response['choices'][0]['finish_reason'] ?? null,
                    'usage' => $response['usage'] ?? [],
                    'model' => $response['model'] ?? $this->model,
                    'response_time_ms' => $responseTime,
                ];

            } catch (ApiException $e) {
                $responseTime = round((microtime(true) - $startTime) * 1000, 2);

                $this->monitor->track(
                    service: $this->getServiceName(),
                    endpoint: $endpoint,
                    responseTimeMs: $responseTime,
                    success: false,
                    statusCode: $e->getStatusCode(),
                    error: $e->getMessage()
                );

                $this->fallback->recordFailure($this->getServiceName());

                throw $e;
            }
        });
    }

    /**
     * Simple chat completion with single message
     *
     * @param string $message User message
     * @param array $context Previous conversation context
     * @return string AI response content
     */
    public function chatSimple(string $message, array $context = []): string
    {
        $messages = $context;
        $messages[] = ['role' => 'user', 'content' => $message];

        try {
            $response = $this->chat($messages);
            return $response['content'] ?? '';
        } catch (\Throwable $e) {
            Log::error('Fireworks chat failed', ['error' => $e->getMessage()]);
            return $this->fallback->getDefault('fireworks', 'chat_response');
        }
    }

    /**
     * Chat with response caching (13.7)
     *
     * @param string $message User message
     * @param array $context Conversation context
     * @param int|null $cacheTtl Cache time in seconds (null for no cache)
     * @return string AI response
     */
    public function chatWithCache(string $message, array $context = [], ?int $cacheTtl = null): string
    {
        $cacheTtl = $cacheTtl ?? config('services.external_api.cache.ai_response_ttl', 3600);

        // Create cache key from message + context hash
        $cacheKey = 'ai:' . md5(serialize($message) . serialize($context));

        // Check cache
        if ($cached = Cache::get($cacheKey)) {
            Log::debug('AI response from cache', ['cache_key' => $cacheKey]);
            return $cached;
        }

        // Get fresh response
        $response = $this->chatSimple($message, $context);

        // Cache if not empty
        if (!empty($response) && $cacheTtl > 0) {
            Cache::put($cacheKey, $response, $cacheTtl);
        }

        return $response;
    }

    /**
     * Check if messages array has a system message
     */
    protected function hasSystemMessage(array $messages): bool
    {
        foreach ($messages as $message) {
            if (($message['role'] ?? '') === 'system') {
                return true;
            }
        }
        return false;
    }

    /**
     * Override authentication for Fireworks (uses API key in header)
     */
    protected function authenticate(\Illuminate\Http\Client\PendingRequest $client): \Illuminate\Http\Client\PendingRequest
    {
        return $client->withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
        ]);
    }

    /**
     * Get available models
     */
    public function getModels(): array
    {
        return $this->circuitBreaker->call($this->getServiceName(), function () {
            return $this->get('/models');
        });
    }

    /**
     * Validate API key by making a test request
     */
    public function validateConnection(): bool
    {
        try {
            $this->chat([
                ['role' => 'user', 'content' => 'Hi']
            ], ['max_tokens' => 5]);
            return true;
        } catch (\Throwable $e) {
            Log::error('Fireworks connection validation failed', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Get service status with health check
     */
    public function getStatus(): array
    {
        $circuitStatus = $this->circuitBreaker->getStatus($this->getServiceName());
        $health = $this->fallback->checkServiceHealth($this->getServiceName());
        $metrics = $this->monitor->getServiceMetrics($this->getServiceName(), 5);

        return [
            'service' => $this->getServiceName(),
            'circuit_breaker' => $circuitStatus,
            'health' => $health,
            'metrics' => $metrics,
            'model' => $this->model,
            'config' => [
                'timeout' => $this->timeout,
                'max_retries' => $this->maxRetries,
                'max_tokens' => $this->maxTokens,
            ],
        ];
    }
}
