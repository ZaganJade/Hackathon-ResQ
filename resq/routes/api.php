<?php

use App\Http\Controllers\LocationRiskController;
use App\Http\Controllers\Webhook\WhatsAppWebhookController;
use Illuminate\Support\Facades\Route;

/**
 * API Routes for ResQ
 *
 * Public API endpoints for 3rd party integrations
 */

// WhatsApp Webhook API (Public with API Key)
Route::prefix('v1/webhook')->group(function () {

    // WhatsApp notification endpoints
    Route::prefix('whatsapp')->group(function () {
        // Send WhatsApp message to single number (POST)
        Route::post('/send', [WhatsAppWebhookController::class, 'send'])
            ->name('api.webhook.whatsapp.send');

        // Broadcast WhatsApp message to all subscribers (POST)
        Route::post('/broadcast', [WhatsAppWebhookController::class, 'broadcast'])
            ->name('api.webhook.whatsapp.broadcast');

        // Check service status (GET)
        Route::get('/status', [WhatsAppWebhookController::class, 'status'])
            ->name('api.webhook.whatsapp.status');
    });

});

// Location-based Risk Analysis API
Route::prefix('v1/location')->group(function () {

    // Quick zone status check (GET)
    Route::get('/status', [LocationRiskController::class, 'quickStatus'])
        ->name('api.location.status');

    // Full zone analysis (POST)
    Route::post('/analyze', [LocationRiskController::class, 'analyze'])
        ->name('api.location.analyze');

    // Get nearby disasters (GET)
    Route::get('/nearby-disasters', [LocationRiskController::class, 'nearbyDisasters'])
        ->name('api.location.nearby');

    // Reverse geocode coordinates (GET)
    Route::get('/reverse-geocode', [LocationRiskController::class, 'reverseGeocode'])
        ->name('api.location.geocode');

    // Location-aware chat with AI (POST - requires auth)
    Route::middleware(['auth:sanctum'])->post('/chat', [LocationRiskController::class, 'chat'])
        ->name('api.location.chat');
});

// Health check endpoint (no auth required)
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'ResQ API',
        'timestamp' => now()->toIso8601String(),
    ]);
})->name('api.health');
