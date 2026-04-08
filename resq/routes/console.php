<?php

use App\Services\ExternalApi\ApiMonitor;
use App\Services\ExternalApi\CircuitBreaker;
use App\Services\ExternalApi\FireworksService;
use App\Services\ExternalApi\GoogleMapsService;
use App\Services\ExternalApi\WhatsAppService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// =============================================================================
// Task 13: API Integration Console Commands
// =============================================================================

// API Health Check Command
Artisan::command('api:health-check', function (
    FireworksService $fireworks,
    GoogleMapsService $googleMaps,
    WhatsAppService $whatsApp
) {
    $this->info('Checking external API health...');

    // Fireworks
    $this->line('');
    $this->info('Fireworks AI:');
    $fireworksStatus = $fireworks->validateConnection();
    $this->line($fireworksStatus ? '  ✓ Connected' : '  ✗ Failed');

    // Google Maps
    $this->line('');
    $this->info('Google Maps:');
    $mapsStatus = $googleMaps->validateConnection();
    $this->line($mapsStatus ? '  ✓ Connected' : '  ✗ Failed');

    // WhatsApp
    $this->line('');
    $this->info('WhatsApp:');
    $waStatus = $whatsApp->checkHealth();
    $this->line('  Status: ' . ($waStatus['status'] ?? 'unknown'));
    $this->line('  Account: ' . ($waStatus['account'] ?? 'unknown'));

    return 0;
})->purpose('Check external API health status');

// API Status Command (detailed)
Artisan::command('api:status', function (
    FireworksService $fireworks,
    GoogleMapsService $googleMaps,
    WhatsAppService $whatsApp,
    CircuitBreaker $circuitBreaker,
    ApiMonitor $monitor
) {
    $this->info('=== External API Status ===');

    // Fireworks
    $this->line('');
    $fwStatus = $fireworks->getStatus();
    $this->info('Fireworks AI');
    $this->line('  Circuit: ' . ($fwStatus['circuit_breaker']['state'] ?? 'unknown'));
    $this->line('  Health: ' . ($fwStatus['health']['status'] ?? 'unknown'));
    $this->line('  Failures: ' . ($fwStatus['circuit_breaker']['failure_count'] ?? 0));

    // Google Maps
    $this->line('');
    $gmStatus = $googleMaps->getStatus();
    $this->info('Google Maps');
    $this->line('  Circuit: ' . ($gmStatus['circuit_breaker']['state'] ?? 'unknown'));
    $this->line('  Health: ' . ($gmStatus['health']['status'] ?? 'unknown'));

    // WhatsApp
    $this->line('');
    $waStatus = $whatsApp->getStatus();
    $this->info('WhatsApp');
    $this->line('  Circuit: ' . ($waStatus['circuit_breaker']['state'] ?? 'unknown'));
    $this->line('  Health: ' . ($waStatus['health']['status'] ?? 'unknown'));

    // Metrics summary
    $this->line('');
    $this->info('Metrics (last 5 minutes)');
    $metrics = $monitor->getAllMetrics(5);
    foreach ($metrics as $service => $data) {
        $this->line("  {$service}: {$data['total_requests']} reqs, " .
            round($data['success_rate'] * 100, 1) . '% success, ' .
            round($data['avg_response_time_ms'], 0) . 'ms avg');
    }

    return 0;
})->purpose('Show detailed API status and metrics');

// Circuit Breaker Control Commands
Artisan::command('api:circuit:open {service}', function (string $service) {
    $circuitBreaker = app(\App\Services\ExternalApi\CircuitBreaker::class);
    $circuitBreaker->forceOpen($service);
    $this->info("Circuit breaker for {$service} opened");
})->purpose('Force open circuit breaker for a service');

Artisan::command('api:circuit:close {service}', function (string $service) {
    $circuitBreaker = app(\App\Services\ExternalApi\CircuitBreaker::class);
    $circuitBreaker->forceClose($service);
    $this->info("Circuit breaker for {$service} closed");
})->purpose('Force close (reset) circuit breaker for a service');

Artisan::command('api:circuit:status', function () {
    $circuitBreaker = app(\App\Services\ExternalApi\CircuitBreaker::class);
    $status = $circuitBreaker->getAllStatus();

    $this->info('Circuit Breaker Status');
    foreach ($status as $service => $data) {
        $this->line("  {$service}: {$data['state']}, " .
            "failures: {$data['failure_count']}/{$data['failure_threshold']}");
    }
})->purpose('Show circuit breaker status for all services');

// Test Commands
Artisan::command('api:test:ai {message?}', function (string $message = 'Halo, apa kabar?') {
    $ai = app(FireworksService::class);
    $this->info('Testing AI...');

    try {
        $response = $ai->chatSimple($message);
        $this->info('Response:');
        $this->line($response);
    } catch (\Throwable $e) {
        $this->error('Failed: ' . $e->getMessage());
    }
})->purpose('Test Fireworks AI with a message');

Artisan::command('api:test:geocode {address?}', function (string $address = 'Monas, Jakarta') {
    $maps = app(GoogleMapsService::class);
    $this->info("Geocoding: {$address}");

    try {
        $result = $maps->geocode($address);
        if ($result) {
            $this->info('Result:');
            $this->line('  Address: ' . $result['address']);
            $this->line('  Lat: ' . $result['lat']);
            $this->line('  Lng: ' . $result['lng']);
        } else {
            $this->warn('No results found');
        }
    } catch (\Throwable $e) {
        $this->error('Failed: ' . $e->getMessage());
    }
})->purpose('Test Google Maps geocoding');

Artisan::command('api:test:whatsapp {phone} {--message=}', function (string $phone) {
    $wa = app(WhatsAppService::class);
    $message = $this->option('message') ?? 'Test message from ResQ';

    $this->info("Sending to: {$phone}");

    try {
        $result = $wa->send($phone, $message);
        $this->info('Sent!');
        $this->line('  Status: ' . $result['status']);
        $this->line('  Message ID: ' . ($result['message_id'] ?? 'N/A'));
    } catch (\Throwable $e) {
        $this->error('Failed: ' . $e->getMessage());
    }
})->purpose('Test WhatsApp sending');

// Cleanup Command
Artisan::command('api:cleanup {--days=30}', function () {
    $days = (int) $this->option('days');
    $monitor = app(ApiMonitor::class);

    $this->info("Cleaning up API metrics older than {$days} days...");
    $deleted = $monitor->cleanup($days);
    $this->info("Deleted {$deleted} records");
})->purpose('Clean up old API metrics');

// =============================================================================
// Scheduled Tasks (Task 13.9 - Monitoring)
// =============================================================================

Schedule::call(function () {
    // Cleanup old metrics daily
    app(ApiMonitor::class)->cleanup(30);
})->daily();

Schedule::call(function () {
    // Process pending WhatsApp messages every 5 minutes
    app(WhatsAppService::class)->processPendingMessages();
})->everyFiveMinutes();
