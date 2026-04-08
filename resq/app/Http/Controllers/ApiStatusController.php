<?php

namespace App\Http\Controllers;

use App\Providers\ExternalApiServiceProvider;
use App\Services\ExternalApi\CircuitBreaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * API Status Controller
 * Provides HTTP endpoints for monitoring external API status
 * Used by Task 11 (Admin Dashboard)
 */
class ApiStatusController extends Controller
{
    /**
     * Get comprehensive API status for all external services
     */
    public function index(): JsonResponse
    {
        try {
            $status = ExternalApiServiceProvider::getAllStatus();

            return response()->json([
                'success' => true,
                'data' => $status,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to get API status', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to retrieve API status',
            ], 500);
        }
    }

    /**
     * Get circuit breaker status
     */
    public function circuitStatus(CircuitBreaker $circuitBreaker): JsonResponse
    {
        try {
            $status = $circuitBreaker->getAllStatus();

            return response()->json([
                'success' => true,
                'data' => $status,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Control circuit breaker
     */
    public function controlCircuit(
        string $service,
        string $action,
        CircuitBreaker $circuitBreaker
    ): JsonResponse {
        try {
            match ($action) {
                'open' => $circuitBreaker->forceOpen($service),
                'close' => $circuitBreaker->forceClose($service),
                default => throw new \InvalidArgumentException("Invalid action: {$action}"),
            };

            return response()->json([
                'success' => true,
                'message' => "Circuit breaker for {$service} has been {$action}ed",
                'status' => $circuitBreaker->getStatus($service),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Health check endpoint for monitoring systems
     */
    public function health(): JsonResponse
    {
        $services = [
            'fireworks' => false,
            'google_maps' => false,
            'whatsapp' => false,
        ];

        try {
            $ai = app(\App\Services\ExternalApi\FireworksService::class);
            $services['fireworks'] = $ai->validateConnection();
        } catch (\Throwable $e) {
            Log::warning('Fireworks health check failed', ['error' => $e->getMessage()]);
        }

        try {
            $maps = app(\App\Services\ExternalApi\GoogleMapsService::class);
            $services['google_maps'] = $maps->validateConnection();
        } catch (\Throwable $e) {
            Log::warning('Google Maps health check failed', ['error' => $e->getMessage()]);
        }

        try {
            $wa = app(\App\Services\ExternalApi\WhatsAppService::class);
            $waStatus = $wa->checkHealth();
            $services['whatsapp'] = $waStatus['status'] === 'healthy';
        } catch (\Throwable $e) {
            Log::warning('WhatsApp health check failed', ['error' => $e->getMessage()]);
        }

        $allHealthy = !in_array(false, $services, true);

        return response()->json([
            'status' => $allHealthy ? 'healthy' : 'degraded',
            'services' => $services,
            'timestamp' => now()->toIso8601String(),
        ], $allHealthy ? 200 : 503);
    }
}
