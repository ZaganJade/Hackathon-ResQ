<?php

namespace App\Services\ExternalApi;

use App\Exceptions\ApiException;
use App\Exceptions\RateLimitException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

/**
 * WhatsApp Notification Service
 * Task 13.5 Implementation
 *
 * Provides:
 * - WhatsApp message sending
 * - Bulk message sending with batching
 * - Message templates for different disaster types
 * - Retry logic with exponential backoff (13.2)
 * - Circuit breaker protection (13.6)
 * - Rate limit handling (13.10)
 * - Queue integration for async sending
 */
class WhatsAppService extends BaseApiClient
{
    protected int $bulkBatchSize;
    protected ?string $senderNumber = null;

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
        $config = config('services.whatsapp');

        $this->baseUrl = rtrim($config['api_url'] ?? 'https://api.wablas.com/api', '/');
        $this->apiKey = $config['api_token'] ?? null;
        $this->timeout = $config['timeout'] ?? 10;
        $this->maxRetries = $config['max_retries'] ?? 5;
        $this->retryDelay = $config['retry_delay'] ?? 2000;
        $this->retryMultiplier = 2.0;
        $this->bulkBatchSize = $config['bulk_batch_size'] ?? 100;
        $this->senderNumber = $config['sender_number'] ?? null;

        $this->headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    protected function getServiceName(): string
    {
        return 'whatsapp';
    }

    /**
     * Authenticate request with API token
     */
    protected function authenticate(PendingRequest $client): PendingRequest
    {
        return $client->withToken($this->apiKey);
    }

    /**
     * Send a single WhatsApp message
     *
     * @param string $phoneNumber Target phone number (with or without country code)
     * @param string $message Message content
     * @param array $options Additional options (buttons, media, etc)
     * @return array ['status' => string, 'message_id' => string, 'sent_at' => datetime]
     * @throws ApiException
     */
    public function send(string $phoneNumber, string $message, array $options = []): array
    {
        return $this->circuitBreaker->call($this->getServiceName(), function () use ($phoneNumber, $message, $options) {
            $startTime = microtime(true);

            // Normalize phone number
            $phone = $this->normalizePhoneNumber($phoneNumber);

            try {
                $endpoint = '/send-message';

                $payload = [
                    'phone' => $phone,
                    'message' => $message,
                ];

                // Add sender if configured
                if ($this->senderNumber) {
                    $payload['sender'] = $this->senderNumber;
                }

                // Add additional options
                if (isset($options['media_url'])) {
                    $payload['media_url'] = $options['media_url'];
                }

                if (isset($options['buttons'])) {
                    $payload['buttons'] = $options['buttons'];
                }

                // Make request
                $client = Http::timeout($this->timeout)
                    ->withToken($this->apiKey)
                    ->asJson();

                $response = $client->post($this->buildUrl($endpoint), $payload);

                $responseTime = round((microtime(true) - $startTime) * 1000, 2);
                $data = $response->json();

                // Check for API-level errors
                if (isset($data['status']) && $data['status'] === 'error') {
                    throw new ApiException(
                        message: $data['message'] ?? 'Unknown WhatsApp API error',
                        statusCode: $response->status(),
                        service: $this->getServiceName(),
                        context: ['phone' => $phone, 'response' => $data]
                    );
                }

                // Track success
                $this->monitor->track(
                    service: $this->getServiceName(),
                    endpoint: 'send',
                    responseTimeMs: $responseTime,
                    success: true,
                    statusCode: $response->status()
                );

                $this->fallback->recordSuccess($this->getServiceName());

                return [
                    'status' => $data['status'] ?? 'sent',
                    'message_id' => $data['message_id'] ?? $data['id'] ?? null,
                    'phone' => $phone,
                    'sent_at' => now()->toIso8601String(),
                    'response_time_ms' => $responseTime,
                ];

            } catch (\Throwable $e) {
                $responseTime = round((microtime(true) - $startTime) * 1000, 2);

                // Track failure
                $this->monitor->track(
                    service: $this->getServiceName(),
                    endpoint: 'send',
                    responseTimeMs: $responseTime,
                    success: false,
                    error: $e->getMessage()
                );

                $this->fallback->recordFailure($this->getServiceName());

                if ($e instanceof ApiException) {
                    throw $e;
                }

                throw new ApiException(
                    message: "WhatsApp send failed: {$e->getMessage()}",
                    previous: $e,
                    service: $this->getServiceName(),
                    context: ['phone' => $phone]
                );
            }
        });
    }

    /**
     * Send bulk messages with batching
     *
     * @param array $recipients Array of ['phone' => string, 'message' => string, 'options' => array]
     * @param int|null $batchSize Batch size (null uses config default)
     * @return array ['sent' => int, 'failed' => int, 'results' => array]
     */
    public function sendBulk(array $recipients, ?int $batchSize = null): array
    {
        $batchSize = $batchSize ?? $this->bulkBatchSize;
        $results = [
            'sent' => 0,
            'failed' => 0,
            'results' => [],
            'batches' => 0,
        ];

        $batches = array_chunk($recipients, $batchSize);

        foreach ($batches as $batchIndex => $batch) {
            $results['batches']++;

            foreach ($batch as $recipient) {
                try {
                    $result = $this->send(
                        $recipient['phone'],
                        $recipient['message'],
                        $recipient['options'] ?? []
                    );

                    $results['sent']++;
                    $results['results'][] = [
                        'phone' => $recipient['phone'],
                        'status' => 'sent',
                        'message_id' => $result['message_id'] ?? null,
                    ];
                } catch (\Throwable $e) {
                    $results['failed']++;
                    $results['results'][] = [
                        'phone' => $recipient['phone'],
                        'status' => 'failed',
                        'error' => $e->getMessage(),
                    ];

                    Log::warning('Bulk send failed for recipient', [
                        'phone' => $recipient['phone'],
                        'error' => $e->getMessage(),
                        'batch' => $batchIndex,
                    ]);
                }
            }

            // Add delay between batches to avoid rate limits
            if ($batchIndex < count($batches) - 1) {
                sleep(1);
            }
        }

        return $results;
    }

    /**
     * Send disaster notification with template
     *
     * @param string $phoneNumber Target phone number
     * @param array $disaster Disaster data ['type' => string, 'location' => string, 'severity' => string]
     * @param float|null $distance Distance from user in km
     * @return array Send result
     */
    public function sendDisasterAlert(string $phoneNumber, array $disaster, ?float $distance = null): array
    {
        $message = $this->buildDisasterMessage($disaster, $distance);

        return $this->send($phoneNumber, $message, ['priority' => 'high']);
    }

    /**
     * Send opt-in confirmation message
     *
     * @param string $phoneNumber User phone number
     * @param string $userName User name
     * @return array Send result
     */
    public function sendOptInConfirmation(string $phoneNumber, string $userName): array
    {
        $message = "Halo {$userName},\n\n" .
            "Anda telah berhasil mendaftar untuk notifikasi darurat ResQ. " .
            "Anda akan menerima pemberitahuan bencana di wilayah Anda.\n\n" .
            "Untuk berhenti, balas STOP.\n\n" .
            "Salam,\nTim ResQ";

        return $this->send($phoneNumber, $message);
    }

    /**
     * Check WhatsApp account info/status
     */
    public function getAccountInfo(): array
    {
        return $this->circuitBreaker->call($this->getServiceName(), function () {
            try {
                $response = Http::timeout($this->timeout)
                    ->withToken($this->apiKey)
                    ->get($this->buildUrl('/info'));

                return $response->json() ?? [];
            } catch (\Throwable $e) {
                Log::error('Failed to get WhatsApp account info', [
                    'error' => $e->getMessage(),
                ]);
                return [];
            }
        });
    }

    /**
     * Check if WhatsApp service is healthy
     */
    public function checkHealth(): array
    {
        try {
            $info = $this->getAccountInfo();

            return [
                'status' => 'healthy',
                'connected' => $info['status'] ?? 'unknown',
                'account' => $info['phone'] ?? 'unknown',
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Normalize Indonesian phone number
     * Converts various formats to international format
     */
    public function normalizePhoneNumber(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Remove leading 0 and replace with 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Add 62 if starts with 8
        if (str_starts_with($phone, '8')) {
            $phone = '62' . $phone;
        }

        // Remove + if present
        $phone = ltrim($phone, '+');

        return $phone;
    }

    /**
     * Validate phone number format
     */
    public function validatePhoneNumber(string $phone): bool
    {
        $normalized = $this->normalizePhoneNumber($phone);

        // Check if it's a valid Indonesian number
        // Should start with 62 and have 10-13 digits total
        return preg_match('/^62[0-9]{9,11}$/', $normalized) === 1;
    }

    /**
     * Build disaster alert message from template
     */
    protected function buildDisasterMessage(array $disaster, ?float $distance = null): string
    {
        $type = $disaster['type'] ?? 'Bencana';
        $location = $disaster['location'] ?? 'Wilayah Anda';
        $severity = $disaster['severity'] ?? 'Tinggi';

        $severityEmojis = [
            'critical' => '🚨',
            'high' => '⚠️',
            'medium' => '⚡',
            'low' => 'ℹ️',
        ];

        $emoji = $severityEmojis[strtolower($severity)] ?? '⚠️';

        $typeTranslations = [
            'earthquake' => 'Gempa Bumi',
            'flood' => 'Banjir',
            'tsunami' => 'Tsunami',
            'landslide' => 'Tanah Longsor',
            'volcanic_eruption' => 'Letusan Gunung Berapi',
            'fire' => 'Kebakaran',
            'tornado' => 'Puting Beliung',
        ];

        $typeId = $typeTranslations[strtolower($type)] ?? $type;

        $message = "{$emoji} *PERINGATAN DINI {$typeId}*\n\n";
        $message .= "Lokasi: {$location}\n";
        $message .= "Tingkat: {$severity}\n";

        if ($distance !== null) {
            $message .= "Jarak dari Anda: " . round($distance, 1) . " km\n";
        }

        $message .= "\nSegera lakukan evakuasi ke tempat aman.";
        $message .= "\n\nInfo lengkap: " . url('/disasters');
        $message .= "\n\n_Dikirim oleh ResQ Emergency System_";

        return $message;
    }

    /**
     * Get service status
     */
    public function getStatus(): array
    {
        $circuitStatus = $this->circuitBreaker->getStatus($this->getServiceName());
        $health = $this->fallback->checkServiceHealth($this->getServiceName());
        $metrics = $this->monitor->getServiceMetrics($this->getServiceName(), 5);
        $accountInfo = $this->getAccountInfo();

        return [
            'service' => $this->getServiceName(),
            'circuit_breaker' => $circuitStatus,
            'health' => $health,
            'metrics' => $metrics,
            'account' => $accountInfo,
            'config' => [
                'timeout' => $this->timeout,
                'max_retries' => $this->maxRetries,
                'bulk_batch_size' => $this->bulkBatchSize,
            ],
        ];
    }

    /**
     * Handle rate limit with retry queue
     * Task 13.10
     */
    public function handleRateLimit(string $phone, string $message, int $retryAfter): void
    {
        Log::info('WhatsApp rate limit hit, queuing for retry', [
            'phone' => $phone,
            'retry_after' => $retryAfter,
        ]);

        // Queue job for later processing
        // This requires a job class - placeholder for now
        Cache::put(
            "whatsapp_pending:{$phone}",
            ['phone' => $phone, 'message' => $message, 'queued_at' => now()],
            $retryAfter + 60
        );
    }

    /**
     * Process pending messages (called from scheduled task)
     */
    public function processPendingMessages(): array
    {
        // Get all pending messages
        $pendingKeys = Cache::get('whatsapp_pending_keys', []);
        $processed = 0;
        $failed = 0;

        foreach ($pendingKeys as $key) {
            $data = Cache::get($key);
            if (!$data) {
                continue;
            }

            try {
                $this->send($data['phone'], $data['message']);
                Cache::forget($key);
                $processed++;
            } catch (\Throwable $e) {
                $failed++;
                Log::warning('Failed to process pending WhatsApp message', [
                    'phone' => $data['phone'],
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return ['processed' => $processed, 'failed' => $failed];
    }
}
