<?php

namespace App\Providers;

use App\Services\ExternalApi\ApiMetric;
use App\Services\ExternalApi\ApiMonitor;
use App\Services\ExternalApi\ApiRateLimiter;
use App\Services\ExternalApi\CircuitBreaker;
use App\Services\ExternalApi\FallbackManager;
use App\Services\ExternalApi\FireworksService;
use App\Services\ExternalApi\GoogleMapsService;
use App\Services\ExternalApi\WhatsAppService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

/**
 * External API Service Provider
 * Registers all Task 13 services with dependency injection
 */
class ExternalApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register shared services as singletons
        $this->app->singleton(CircuitBreaker::class, function () {
            return new CircuitBreaker();
        });

        $this->app->singleton(ApiMonitor::class, function () {
            return new ApiMonitor();
        });

        $this->app->singleton(FallbackManager::class, function () {
            return new FallbackManager();
        });

        $this->app->singleton(ApiRateLimiter::class, function () {
            return new ApiRateLimiter();
        });

        // Register API services with dependencies
        $this->app->singleton(FireworksService::class, function ($app) {
            return new FireworksService(
                $app->make(CircuitBreaker::class),
                $app->make(ApiMonitor::class),
                $app->make(FallbackManager::class)
            );
        });

        $this->app->singleton(GoogleMapsService::class, function ($app) {
            return new GoogleMapsService(
                $app->make(CircuitBreaker::class),
                $app->make(ApiMonitor::class),
                $app->make(FallbackManager::class)
            );
        });

        $this->app->singleton(WhatsAppService::class, function ($app) {
            return new WhatsAppService(
                $app->make(CircuitBreaker::class),
                $app->make(ApiMonitor::class),
                $app->make(FallbackManager::class)
            );
        });

        // Register facade-like accessors
        $this->app->bind('external.api.ai', FireworksService::class);
        $this->app->bind('external.api.maps', GoogleMapsService::class);
        $this->app->bind('external.api.whatsapp', WhatsAppService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Log service initialization
        Log::info('External API Service Provider initialized', [
            'services' => [
                'fireworks' => config('services.fireworks.api_key') ? 'configured' : 'missing',
                'google_maps' => config('services.google_maps.api_key') ? 'configured' : 'missing',
                'whatsapp' => config('services.whatsapp.api_token') ? 'configured' : 'missing',
            ],
        ]);

        // Register health check endpoints
        $this->app->booted(function () {
            $this->validateConfiguration();
        });
    }

    /**
     * Validate API configuration on boot
     */
    protected function validateConfiguration(): void
    {
        $required = [
            'fireworks' => config('services.fireworks.api_key'),
            'google_maps' => config('services.google_maps.api_key'),
            'whatsapp' => config('services.whatsapp.api_token'),
        ];

        foreach ($required as $service => $configured) {
            if (!$configured) {
                Log::warning("External API '{$service}' is not configured properly");
            }
        }
    }

    /**
     * Get all service status for health monitoring
     */
    public static function getAllStatus(): array
    {
        try {
            $app = app();

            return [
                'fireworks' => $app->make(FireworksService::class)->getStatus(),
                'google_maps' => $app->make(GoogleMapsService::class)->getStatus(),
                'whatsapp' => $app->make(WhatsAppService::class)->getStatus(),
                'circuit_breaker' => $app->make(CircuitBreaker::class)->getAllStatus(),
                'rate_limits' => [
                    'fireworks' => $app->make(ApiRateLimiter::class)->getStatus('fireworks'),
                    'google_maps' => $app->make(ApiRateLimiter::class)->getStatus('google_maps'),
                    'whatsapp' => $app->make(ApiRateLimiter::class)->getStatus('whatsapp'),
                ],
                'timestamp' => now()->toIso8601String(),
            ];
        } catch (\Throwable $e) {
            Log::error('Failed to get service status', ['error' => $e->getMessage()]);
            return ['error' => $e->getMessage()];
        }
    }
}
