<?php

namespace App\Http\Controllers\Webhook\Concerns;

use Illuminate\Http\Request;

trait AuthenticatesWebhookRequests
{
    /**
     * Authenticate webhook request via X-API-Key header.
     */
    private function authenticate(Request $request): bool
    {
        $apiKey = $request->header('X-API-Key');

        if (empty($apiKey)) {
            return false;
        }

        // Get valid API keys from config
        $validKeys = config('services.webhook.api_keys', []);

        // For single key setup (backward compatibility)
        $primaryKey = config('services.webhook.api_key');
        if ($primaryKey && $apiKey === $primaryKey) {
            return true;
        }

        // For multiple keys setup
        return in_array($apiKey, $validKeys, true);
    }
}
