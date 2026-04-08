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

    'google' => [
        'maps_api_key' => env('GOOGLE_MAPS_API_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | WhatsApp API (Yobase)
    |--------------------------------------------------------------------------
    |
    | Configuration for Yobase WhatsApp API gateway.
    | API docs: https://yobase.io/docs/api
    |
    */

    'whatsapp' => [
        'provider' => env('WHATSAPP_PROVIDER', 'yobase'), // yobase, wablas, twilio
        'api_url' => env('WHATSAPP_API_URL', 'https://whats.yobase.me/api'),
        'api_token' => env('WHATSAPP_API_TOKEN'),
        'sender_number' => env('WHATSAPP_SENDER_NUMBER'), // Registered number
        'timeout' => env('WHATSAPP_TIMEOUT', 30),
        'max_retries' => env('WHATSAPP_MAX_RETRIES', 3),
        'retry_delay' => env('WHATSAPP_RETRY_DELAY', 2000),
        'bulk_batch_size' => env('WHATSAPP_BULK_BATCH_SIZE', 100),
    ],

    /*
    |--------------------------------------------------------------------------
    | Fireworks AI
    |--------------------------------------------------------------------------
    |
    | Configuration for Fireworks AI API (Task 13.1)
    |
    */

    'fireworks' => [
        'api_key' => env('FIREWORKS_API_KEY'),
        'timeout' => env('FIREWORKS_TIMEOUT', 30),
        'max_retries' => env('FIREWORKS_MAX_RETRIES', 3),
        'retry_delay' => env('FIREWORKS_RETRY_DELAY', 1000),
        'model' => env('FIREWORKS_MODEL', 'accounts/fireworks/routers/kimi-k2p5-turbo'),
    ],

    /*
    |--------------------------------------------------------------------------
    | External API Service Configuration
    |--------------------------------------------------------------------------
    |
    | Settings for external API resilience features (Task 13)
    |
    */

    'external_api' => [
        'cache' => [
            'enabled' => true,
            'ttl' => env('EXTERNAL_API_CACHE_TTL', 3600),
            'ai_response_ttl' => env('AI_RESPONSE_CACHE_TTL', 3600),
        ],
        'circuit_breaker' => [
            'failure_threshold' => env('CIRCUIT_BREAKER_THRESHOLD', 5),
            'timeout' => env('CIRCUIT_BREAKER_TIMEOUT', 60),
        ],
        'monitoring' => [
            'alert_threshold' => env('API_MONITOR_ALERT_THRESHOLD', 0.1),
            'window_minutes' => env('API_MONITOR_WINDOW_MINUTES', 5),
            'slack_webhook' => env('API_MONITOR_SLACK_WEBHOOK'),
            'log_enabled' => env('API_MONITOR_LOG_ENABLED', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook API Keys
    |--------------------------------------------------------------------------
    |
    | API keys for 3rd party webhook access (BMKG, etc.)
    |
    */

    'webhook' => [
        'api_key' => env('WEBHOOK_API_KEY'), // Primary key for single client
        'api_keys' => explode(',', env('WEBHOOK_API_KEYS', '')), // Multiple keys for multiple clients
    ],

];
