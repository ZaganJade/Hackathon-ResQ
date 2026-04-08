<?php

namespace App\Services\ExternalApi;

use App\Models\ApiMetric;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * API Usage Monitoring and Alerting
 * Task 13.9 Implementation
 *
 * Tracks:
 * - Response times per service
 * - Success/failure rates
 * - Error patterns
 * - Sends alerts when thresholds exceeded
 */
class ApiMonitor
{
    protected float $alertThreshold;
    protected int $windowMinutes;
    protected ?string $slackWebhook;
    protected bool $logEnabled;

    public function __construct()
    {
        $config = config('services.external_api.monitoring') ?? [];
        $this->alertThreshold = $config['alert_threshold'] ?? 0.1; // 10%
        $this->windowMinutes = $config['window_minutes'] ?? 5;
        $this->slackWebhook = $config['slack_webhook'] ?? null;
        $this->logEnabled = $config['log_enabled'] ?? true;
    }

    /**
     * Track API request metrics
     */
    public function track(
        string $service,
        string $endpoint,
        float $responseTimeMs,
        bool $success,
        ?int $statusCode = null,
        ?string $error = null
    ): void {
        // Store in database for historical analysis
        try {
            ApiMetric::create([
                'service' => $service,
                'endpoint' => $endpoint,
                'response_time_ms' => $responseTimeMs,
                'success' => $success,
                'status_code' => $statusCode,
                'error_message' => $error,
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Don't let monitoring failures break the app
            Log::warning('Failed to store API metric', [
                'error' => $e->getMessage(),
                'service' => $service,
            ]);
        }

        // Update real-time window stats
        $this->updateWindowStats($service, $success, $responseTimeMs);

        // Log detailed request info
        if ($this->logEnabled) {
            $level = $success ? 'debug' : 'warning';
            Log::{$level}("API {$service} request", [
                'endpoint' => $endpoint,
                'response_time_ms' => $responseTimeMs,
                'success' => $success,
                'status_code' => $statusCode,
            ]);
        }

        // Check if we need to send alert
        if (!$success) {
            $this->checkAlertThreshold($service);
        }
    }

    /**
     * Get metrics summary for a service
     */
    public function getServiceMetrics(string $service, ?int $minutes = null): array
    {
        $since = now()->subMinutes($minutes ?? $this->windowMinutes);

        $metrics = ApiMetric::where('service', $service)
            ->where('created_at', '>=', $since)
            ->get();

        $total = $metrics->count();
        $success = $metrics->where('success', true)->count();
        $failed = $total - $success;

        return [
            'service' => $service,
            'total_requests' => $total,
            'successful' => $success,
            'failed' => $failed,
            'success_rate' => $total > 0 ? round($success / $total, 4) : 0,
            'avg_response_time_ms' => $metrics->avg('response_time_ms') ?? 0,
            'max_response_time_ms' => $metrics->max('response_time_ms') ?? 0,
            'min_response_time_ms' => $metrics->min('response_time_ms') ?? 0,
            'p95_response_time_ms' => $this->calculatePercentile($metrics->pluck('response_time_ms')->toArray(), 95),
            'window_minutes' => $minutes ?? $this->windowMinutes,
        ];
    }

    /**
     * Get metrics for all services
     */
    public function getAllMetrics(?int $minutes = null): array
    {
        $services = ApiMetric::distinct()->pluck('service')->toArray();
        $metrics = [];

        foreach ($services as $service) {
            $metrics[$service] = $this->getServiceMetrics($service, $minutes);
        }

        return $metrics;
    }

    /**
     * Get real-time statistics from cache window
     */
    public function getRealtimeStats(string $service): array
    {
        $cacheKey = "api_stats:{$service}";
        $stats = Cache::get($cacheKey, [
            'requests' => [],
            'total' => 0,
            'success' => 0,
            'failed' => 0,
        ]);

        // Filter out old entries
        $cutoff = now()->subMinutes($this->windowMinutes)->getTimestamp();
        $stats['requests'] = array_filter($stats['requests'], fn($r) => $r['timestamp'] >= $cutoff);

        // Recalculate
        $stats['total'] = count($stats['requests']);
        $stats['success'] = count(array_filter($stats['requests'], fn($r) => $r['success']));
        $stats['failed'] = $stats['total'] - $stats['success'];

        return $stats;
    }

    /**
     * Send test notification to verify alerting works
     */
    public function sendTestAlert(): bool
    {
        if (!$this->slackWebhook) {
            return false;
        }

        try {
            $response = Http::post($this->slackWebhook, [
                'text' => '🧪 ResQ API Monitor Test Alert',
                'blocks' => [
                    [
                        'type' => 'header',
                        'text' => [
                            'type' => 'plain_text',
                            'text' => 'ResQ API Monitoring Test',
                            'emoji' => true,
                        ],
                    ],
                    [
                        'type' => 'section',
                        'text' => [
                            'type' => 'mrkdwn',
                            'text' => 'This is a test message to verify alerting is configured correctly.',
                        ],
                    ],
                    [
                        'type' => 'context',
                        'elements' => [
                            [
                                'type' => 'mrkdwn',
                                'text' => 'Time: ' . now()->toIso8601String(),
                            ],
                        ],
                    ],
                ],
            ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Failed to send test alert', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Clean up old metrics
     */
    public function cleanup(int $daysToKeep = 30): int
    {
        $cutoff = now()->subDays($daysToKeep);
        $deleted = ApiMetric::where('created_at', '<', $cutoff)->delete();

        Log::info("API metrics cleanup: {$deleted} records deleted older than {$daysToKeep} days");

        return $deleted;
    }

    /**
     * Update sliding window statistics in cache
     */
    protected function updateWindowStats(string $service, bool $success, float $responseTime): void
    {
        $cacheKey = "api_stats:{$service}";
        $stats = Cache::get($cacheKey, ['requests' => [], 'total' => 0, 'success' => 0, 'failed' => 0]);

        // Add new request
        $stats['requests'][] = [
            'timestamp' => now()->getTimestamp(),
            'success' => $success,
            'response_time' => $responseTime,
        ];

        // Keep only last N requests to prevent cache bloat
        $maxEntries = 1000;
        if (count($stats['requests']) > $maxEntries) {
            $stats['requests'] = array_slice($stats['requests'], -$maxEntries);
        }

        // Update counters
        $stats['total'] = count($stats['requests']);
        $stats['success'] = count(array_filter($stats['requests'], fn($r) => $r['success']));
        $stats['failed'] = $stats['total'] - $stats['success'];

        Cache::put($cacheKey, $stats, now()->addMinutes($this->windowMinutes + 1));
    }

    /**
     * Check if alert threshold is exceeded
     */
    protected function checkAlertThreshold(string $service): void
    {
        $stats = $this->getRealtimeStats($service);

        if ($stats['total'] < 5) {
            // Need minimum sample size
            return;
        }

        $failureRate = $stats['failed'] / $stats['total'];

        if ($failureRate >= $this->alertThreshold) {
            $lastAlertKey = "api_alert_sent:{$service}";
            $lastAlert = Cache::get($lastAlertKey);

            // Don't spam alerts - max 1 per 15 minutes
            if ($lastAlert && now()->diffInMinutes($lastAlert) < 15) {
                return;
            }

            $this->sendAlert($service, $failureRate, $stats);
            Cache::put($lastAlertKey, now(), now()->addMinutes(15));
        }
    }

    /**
     * Send alert notification
     */
    protected function sendAlert(string $service, float $failureRate, array $stats): void
    {
        $message = "🚨 High failure rate detected for {$service}: " . round($failureRate * 100, 1) . '%';

        Log::error($message, [
            'service' => $service,
            'failure_rate' => $failureRate,
            'threshold' => $this->alertThreshold,
            'stats' => $stats,
        ]);

        // Slack notification if configured
        if ($this->slackWebhook) {
            try {
                Http::timeout(5)->post($this->slackWebhook, [
                    'text' => $message,
                    'blocks' => [
                        [
                            'type' => 'header',
                            'text' => [
                                'type' => 'plain_text',
                                'text' => '🚨 ResQ API Alert',
                                'emoji' => true,
                            ],
                        ],
                        [
                            'type' => 'section',
                            'fields' => [
                                [
                                    'type' => 'mrkdwn',
                                    'text' => "*Service:*\n{$service}",
                                ],
                                [
                                    'type' => 'mrkdwn',
                                    'text' => "*Failure Rate:*\n" . round($failureRate * 100, 1) . '%',
                                ],
                                [
                                    'type' => 'mrkdwn',
                                    'text' => "*Threshold:*\n" . round($this->alertThreshold * 100, 1) . '%',
                                ],
                                [
                                    'type' => 'mrkdwn',
                                    'text' => "*Window:*\n{$this->windowMinutes} minutes",
                                ],
                            ],
                        ],
                        [
                            'type' => 'section',
                            'text' => [
                                'type' => 'mrkdwn',
                                'text' => "*Request Stats:*\nTotal: {$stats['total']}\nSuccess: {$stats['success']}\nFailed: {$stats['failed']}",
                            ],
                        ],
                        [
                            'type' => 'context',
                            'elements' => [
                                [
                                    'type' => 'mrkdwn',
                                    'text' => 'Time: ' . now()->toIso8601String(),
                                ],
                            ],
                        ],
                    ],
                ]);
            } catch (\Throwable $e) {
                Log::error('Failed to send Slack alert', ['error' => $e->getMessage()]);
            }
        }
    }

    /**
     * Calculate percentile from array of values
     */
    protected function calculatePercentile(array $values, int $percentile): float
    {
        if (empty($values)) {
            return 0;
        }

        sort($values);
        $index = ceil(($percentile / 100) * count($values)) - 1;
        $index = max(0, $index);

        return $values[$index] ?? 0;
    }
}
