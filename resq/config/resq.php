<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the AI Assist feature using Fireworks AI.
    |
    */

    'ai_system_prompt' => env('RESQ_AI_SYSTEM_PROMPT', 'Anda adalah asisten AI ResQ yang membantu masyarakat Indonesia dengan informasi mitigasi bencana. Berikan jawaban singkat, jelas, dan praktis dalam Bahasa Indonesia tentang kesiapsiagaan, respons darurat, dan pemulihan pasca-bencana.'),

    'ai_timeout' => env('RESQ_AI_TIMEOUT', 3),

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Notification
    |--------------------------------------------------------------------------
    |
    | Configuration for WhatsApp notification service.
    |
    */

    'whatsapp' => [
        'api_url' => env('WHATSAPP_API_URL'),
        'api_token' => env('WHATSAPP_API_TOKEN'),
        'retry_attempts' => 3,
        'retry_delay' => 5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Maps
    |--------------------------------------------------------------------------
    |
    | Configuration for Google Maps integration.
    |
    */

    'google_maps' => [
        'api_key' => env('GOOGLE_MAPS_API_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | BMKG Integration
    |--------------------------------------------------------------------------
    |
    | Future configuration for BMKG API integration.
    |
    */

    'bmkg' => [
        'enabled' => env('BMKG_ENABLED', false),
        'api_url' => env('BMKG_API_URL', 'https://data.bmkg.go.id/DataMKG/TEWS/'),
        'sync_interval' => env('BMKG_SYNC_INTERVAL', 300), // seconds
    ],

    /*
    |--------------------------------------------------------------------------
    | Disaster Settings
    |--------------------------------------------------------------------------
    |
    | Default settings for disaster classification and notifications.
    |
    */

    'disaster' => [
        'auto_severity' => true,
        'notification_radius' => 50, // km
        'high_severity_threshold' => [
            'earthquake' => 6.0,
            'flood' => 1.5, // meters
        ],
    ],
];
