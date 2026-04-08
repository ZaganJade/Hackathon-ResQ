<?php

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
        // Send WhatsApp message (POST)
        Route::post('/send', [WhatsAppWebhookController::class, 'send'])
            ->name('api.webhook.whatsapp.send');

        // Check service status (GET)
        Route::get('/status', [WhatsAppWebhookController::class, 'status'])
            ->name('api.webhook.whatsapp.status');
    });

});

// Health check endpoint (no auth required)
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'ResQ API',
        'timestamp' => now()->toIso8601String(),
    ]);
})->name('api.health');
