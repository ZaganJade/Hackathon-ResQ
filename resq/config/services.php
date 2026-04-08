<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Fireworks AI Service (Task 13.1)
    |--------------------------------------------------------------------------
    |
    | Configuration for Fireworks AI API integration used in AI Assist feature.
    |
    */

    'fireworks' => [
        'api_key' => env('FIREWORKS_API_KEY'),
        'endpoint' => env('FIREWORKS_API_ENDPOINT', 'https://api.fireworks.ai/inference/v1/chat/completions'),
        'model' => env('FIREWORKS_MODEL', 'accounts/fireworks/models/llama-v3p1-70b-instruct'),
        'timeout' => env('FIREWORKS_TIMEOUT', 30),
        'max_retries' => env('FIREWORKS_MAX_RETRIES', 3),
        'retry_delay' => env('FIREWORKS_RETRY_DELAY', 1000), // milliseconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Maps Service (Task 13.3, 13.4)
    |--------------------------------------------------------------------------
    |
    | Configuration for Google Maps JavaScript and Geocoding APIs.
    |
    */

    'google_maps' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY'),
        'js_api_key' => env('GOOGLE_MAPS_JS_API_KEY', env('GOOGLE_MAPS_API_KEY')),
        'geocoding_endpoint' => env('GOOGLE_MAPS_GEOCODING_ENDPOINT', 'https://maps.googleapis.com/maps/api/geocode/json'),
        'directions_endpoint' => 'https://maps.googleapis.com/maps/api/directions/json',
        'places_endpoint' => 'https://maps.googleapis.com/maps/api/place/nearbysearch/json',
        'timeout' => env('GOOGLE_MAPS_TIMEOUT', 10),
        'max_retries' => env('GOOGLE_MAPS_MAX_RETRIES', 3),
    ],

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Web API Service (Task 13.5)
    |--------------------------------------------------------------------------
    |
    | Configuration for WhatsApp notification service.
    |
    */

    'whatsapp' => [
        'api_url' => env('WHATSAPP_API_URL', 'https://api.wablas.com/api'),
        'api_token' => env('WHATSAPP_API_TOKEN'),
        'timeout' => env('WHATSAPP_TIMEOUT', 10),
        'max_retries' => env('WHATSAPP_MAX_RETRIES', 5),
        'retry_delay' => env('WHATSAPP_RETRY_DELAY', 2000), // milliseconds
        'bulk_batch_size' => env('WHATSAPP_BULK_BATCH_SIZE', 100),
        'sender_number' => env('WHATSAPP_SENDER_NUMBER'),
    ],

    /*
    |--------------------------------------------------------------------------
    | External API Resilience Configuration (Task 13.6 - 13.10)
    |--------------------------------------------------------------------------
    |
    | Circuit breaker, caching, monitoring, and rate limiting settings.
    |
    */

    'external_api' => [
        // Circuit breaker configuration (13.6)
        'circuit_breaker' => [
            'failure_threshold' => env('CIRCUIT_BREAKER_FAILURE_THRESHOLD', 5),
            'timeout' => env('CIRCUIT_BREAKER_TIMEOUT', 60), // seconds
            'half_open_requests' => env('CIRCUIT_BREAKER_HALF_OPEN_REQUESTS', 3),
        ],

        // Cache TTL settings (13.7)
        'cache' => [
            'geocoding_ttl' => env('API_CACHE_GEOCODING_TTL', 2592000), // 30 days
            'ai_response_ttl' => env('API_CACHE_AI_TTL', 3600), // 1 hour
            'whatsapp_status_ttl' => env('API_CACHE_WHATSAPP_STATUS_TTL', 300), // 5 minutes
            'fallback_ttl' => 604800, // 7 days for stale fallbacks
        ],

        // Monitoring configuration (13.9)
        'monitoring' => [
            'alert_threshold' => env('API_MONITOR_ALERT_THRESHOLD', 0.1), // 10% failure rate
            'window_minutes' => env('API_MONITOR_WINDOW_MINUTES', 5),
            'slack_webhook' => env('API_MONITOR_SLACK_WEBHOOK'),
            'log_enabled' => true,
        ],

        // Rate limiting configuration (13.10)
        'rate_limit' => [
            'retry_enabled' => env('API_RATE_LIMIT_RETRY_ENABLED', true),
            'max_wait_seconds' => env('API_RATE_LIMIT_MAX_WAIT', 300),
            'backoff_multiplier' => 2,
        ],
    ],

];
