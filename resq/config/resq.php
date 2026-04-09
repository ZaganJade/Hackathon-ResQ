<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the AI Assist feature using Fireworks AI.
    | API credentials are configured in config/services.php (Task 13.1)
    |
    */

    'ai_system_prompt' => env('RESQ_AI_SYSTEM_PROMPT', 'Kamu adalah ResQ, asisten mitigasi bencana untuk masyarakat Indonesia. Gaya bicaramu santai tapi tetap sopan dan tegas ketika perlu.

Cara berbicaramu (WAJIB IKUTI):
- Gunakan bahasa sehari-hari yang natural, jangan terlalu formal
- JANGAN PERNAH pakai format markdown seperti **tebal**, _miring_, atau bullet point bintang
- JANGAN pakai nomor berurutan seperti 1. 2. 3., cukup tulis paragraf mengalir
- Bicara seperti teman ngobrol WhatsApp, nggak perlu format rapat
- Tenang tapi tegas saat memberi peringatan bahaya
- Singkat padat, langsung ke intinya
- Kalau mau nyebut yang penting, pakai huruf kapital aja, nggak usah ditandain bintang

Fokusmu:
- Informasi mitigasi dan kesiapsiagaan bencana
- Respons darurat dan evakuasi
- Pemulihan pasca-bencana
- Selalu pertimbangkan konteks lokasi user kalau tersedia'),

    'ai_timeout' => env('RESQ_AI_TIMEOUT', 30),

    'ai_max_tokens' => env('RESQ_AI_MAX_TOKENS', 1024),

    'ai_temperature' => env('RESQ_AI_TEMPERATURE', 0.7),

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Notification
    |--------------------------------------------------------------------------
    |
    | Configuration for WhatsApp notification service.
    | API credentials are configured in config/services.php (Task 13.5)
    |
    */

    'whatsapp' => [
        'enabled' => env('WHATSAPP_ENABLED', true),
        'provider' => env('WHATSAPP_PROVIDER', 'yobase'),
        'retry_attempts' => 3,
        'retry_delay' => 5,
        'notification_radius' => 50, // km
        'max_daily_notifications' => 1000,
        // API credentials are in config/services.php
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Maps
    |--------------------------------------------------------------------------
    |
    | Configuration for Google Maps integration.
    | API credentials are configured in config/services.php (Task 13.3, 13.4)
    |
    */

    'google_maps' => [
        'default_zoom' => 12,
        'map_center_lat' => -6.2088, // Jakarta default
        'map_center_lng' => 106.8456,
        'marker_cluster_threshold' => 100,
        'refresh_interval' => 300, // seconds
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
            'tsunami' => 0.5, // meters wave height
            'volcanic_eruption' => 3, // alert level
            'landslide' => 1, // immediate threat
        ],
        'severity_colors' => [
            'critical' => '#DC2626', // red-600
            'high' => '#EA580C', // orange-600
            'medium' => '#CA8A04', // yellow-600
            'low' => '#16A34A', // green-600
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | External API Service Classes
    |--------------------------------------------------------------------------
    |
    | Service class configuration for dependency injection.
    |
    */

    'services' => [
        'ai' => \App\Services\ExternalApi\FireworksService::class,
        'maps' => \App\Services\ExternalApi\GoogleMapsService::class,
        'whatsapp' => \App\Services\ExternalApi\WhatsAppService::class,
    ],
];
