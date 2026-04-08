# Task 13: API Integration & External Services

Dokumentasi implementasi Task 13 - Integrasi API Eksternal untuk sistem ResQ.

## Overview

Task 13 mengimplementasikan layer integrasi untuk 3 external API dengan 5 layer perlindungan (resilience patterns):

### External APIs
| API | Fungsi | File Service |
|-----|--------|--------------|
| Fireworks AI | AI chat engine | `FireworksService.php` |
| Google Maps | Maps + Geocoding | `GoogleMapsService.php` |
| WhatsApp | Notifikasi bencana | `WhatsAppService.php` |

### 5 Layer Perlindungan
1. **Circuit Breaker** (13.6) - Mencegah cascade failure
2. **Timeout & Retry** (13.2) - Retry dengan exponential backoff
3. **Caching** (13.7) - Cache response untuk performa
4. **Fallback** (13.8) - Alternatif saat API gagal
5. **Rate Limiting** (13.10) - Handle 429 responses

---

## Struktur File

```
app/
├── Exceptions/
│   └── ApiException.php              # Exception classes
├── Http/
│   └── Controllers/
│       └── ApiStatusController.php   # HTTP endpoints
├── Models/
│   └── ApiMetric.php                 # Model untuk metrics
├── Providers/
│   └── ExternalApiServiceProvider.php # DI container bindings
└── Services/
    └── ExternalApi/
        ├── BaseApiClient.php         # Abstract client dengan retry (13.2)
        ├── CircuitBreaker.php        # Circuit breaker pattern (13.6)
        ├── ApiMonitor.php          # Monitoring & alerting (13.9)
        ├── ApiRateLimiter.php        # Rate limit handler (13.10)
        ├── FallbackManager.php       # Fallback mechanisms (13.8)
        ├── FireworksService.php      # Fireworks AI integration (13.1)
        ├── GoogleMapsService.php     # Google Maps integration (13.3, 13.4)
        └── WhatsAppService.php       # WhatsApp integration (13.5)

database/
└── migrations/
    └── 2026_04_08_100000_create_api_metrics_table.php

config/
├── services.php                      # API credentials & settings
└── resq.php                          # ResQ-specific settings

routes/
└── console.php                       # Artisan commands
```

---

## Konfigurasi

### Environment Variables

```env
# Fireworks AI (13.1)
FIREWORKS_API_KEY=your_key
FIREWORKS_API_ENDPOINT=https://api.fireworks.ai/inference/v1/chat/completions
FIREWORKS_MODEL=accounts/fireworks/models/llama-v3p1-70b-instruct
FIREWORKS_TIMEOUT=30
FIREWORKS_MAX_RETRIES=3
FIREWORKS_RETRY_DELAY=1000

# Google Maps (13.3, 13.4)
GOOGLE_MAPS_API_KEY=your_key
GOOGLE_MAPS_JS_API_KEY=your_js_key
GOOGLE_MAPS_GEOCODING_ENDPOINT=https://maps.googleapis.com/maps/api/geocode/json
GOOGLE_MAPS_TIMEOUT=10
GOOGLE_MAPS_MAX_RETRIES=3

# WhatsApp (13.5)
WHATSAPP_API_URL=https://api.wablas.com/api
WHATSAPP_API_TOKEN=your_token
WHATSAPP_TIMEOUT=10
WHATSAPP_MAX_RETRIES=5
WHATSAPP_RETRY_DELAY=2000

# Circuit Breaker (13.6)
CIRCUIT_BREAKER_FAILURE_THRESHOLD=5
CIRCUIT_BREAKER_TIMEOUT=60
CIRCUIT_BREAKER_HALF_OPEN_REQUESTS=3

# Caching (13.7)
API_CACHE_GEOCODING_TTL=2592000  # 30 days
API_CACHE_AI_TTL=3600          # 1 hour
API_CACHE_WHATSAPP_STATUS_TTL=300

# Monitoring (13.9)
API_MONITOR_ALERT_THRESHOLD=0.1  # 10% failure rate
API_MONITOR_WINDOW_MINUTES=5
API_MONITOR_SLACK_WEBHOOK=https://hooks.slack.com/...

# Rate Limiting (13.10)
API_RATE_LIMIT_RETRY_ENABLED=true
API_RATE_LIMIT_MAX_WAIT=300
```

---

## Penggunaan

### Fireworks AI (Task 5 - AI Assist)

```php
use App\Services\ExternalApi\FireworksService;

class AIController extends Controller
{
    public function chat(Request $request, FireworksService $ai)
    {
        // Simple chat
        $response = $ai->chatSimple('Apa yang harus dilakukan saat gempa?');

        // Chat with history context
        $messages = [
            ['role' => 'user', 'content' => 'Halo'],
            ['role' => 'assistant', 'content' => 'Halo! Ada yang bisa saya bantu?'],
        ];
        $response = $ai->chat($messages);

        // Chat with caching
        $response = $ai->chatWithCache('Pertanyaan populer', [], 3600);
    }
}
```

### Google Maps (Task 7 - Disaster Map)

```php
use App\Services\ExternalApi\GoogleMapsService;

class MapController extends Controller
{
    public function geocode(Request $request, GoogleMapsService $maps)
    {
        // Geocode address to coordinates
        $result = $maps->geocode('Monas, Jakarta');
        // Returns: ['lat' => -6.1754, 'lng' => 106.8272, 'address' => '...']

        // Reverse geocode
        $address = $maps->reverseGeocode(-6.1754, 106.8272);

        // Calculate distance
        $distance = $maps->calculateDistance($lat1, $lng1, $lat2, $lng2);

        // Filter points within radius
        $nearby = $maps->filterWithinRadius($disasters, $userLat, $userLng, 50);

        // Get JS API key for frontend
        $jsKey = $maps->getJavaScriptApiKey();
    }
}
```

### WhatsApp (Task 9 - Notifications)

```php
use App\Services\ExternalApi\WhatsAppService;

class NotificationController extends Controller
{
    public function sendAlert(Request $request, WhatsAppService $wa)
    {
        // Send single message
        $result = $wa->send('6281234567890', 'Peringatan bencana!');

        // Send disaster alert with template
        $result = $wa->sendDisasterAlert('6281234567890', [
            'type' => 'flood',
            'location' => 'Jakarta Barat',
            'severity' => 'high',
        ], 5.2); // distance in km

        // Send bulk
        $results = $wa->sendBulk([
            ['phone' => '6281234567890', 'message' => 'Alert 1'],
            ['phone' => '6281234567891', 'message' => 'Alert 2'],
        ]);

        // Validate phone number
        $isValid = $wa->validatePhoneNumber('081234567890');
        $normalized = $wa->normalizePhoneNumber('081234567890'); // 6281234567890
    }
}
```

---

## Console Commands

```bash
# Health check
php artisan api:health-check

# Detailed status
php artisan api:status

# Test APIs
php artisan api:test:ai "Halo, apa kabar?"
php artisan api:test:geocode "Monas, Jakarta"
php artisan api:test:whatsapp 6281234567890 --message="Test"

# Circuit breaker control
php artisan api:circuit:status
php artisan api:circuit:open fireworks
php artisan api:circuit:close google_maps

# Cleanup metrics
php artisan api:cleanup --days=30
```

---

## Circuit Breaker

### States
- **CLOSED**: Normal operation
- **OPEN**: Failure threshold reached, requests fail fast
- **HALF_OPEN**: Testing if service recovered

### Manual Control

```php
use App\Services\ExternalApi\CircuitBreaker;

$circuit = app(CircuitBreaker::class);

// Check status
$status = $circuit->getStatus('fireworks');

// Force open (maintenance mode)
$circuit->forceOpen('fireworks', 300); // 5 minutes

// Force close (reset)
$circuit->forceClose('fireworks');
```

---

## Monitoring & Alerting

### Metrics Tracked
- Response time per endpoint
- Success/failure rates
- Error patterns
- Circuit breaker state changes

### Slack Alerts
Configure webhook di `.env`:
```env
API_MONITOR_SLACK_WEBHOOK=https://hooks.slack.com/services/...
```

### Query Metrics

```php
use App\Services\ExternalApi\ApiMonitor;

$monitor = app(ApiMonitor::class);

// Get metrics for service
$metrics = $monitor->getServiceMetrics('fireworks', 30); // last 30 minutes

// Get all metrics
$all = $monitor->getAllMetrics(60);

// Send test alert
$monitor->sendTestAlert();
```

---

## HTTP Endpoints

### Public Health Check
```
GET /api/health
```

Response:
```json
{
  "status": "healthy",
  "services": {
    "fireworks": true,
    "google_maps": true,
    "whatsapp": false
  },
  "timestamp": "2026-04-08T12:00:00Z"
}
```

### Admin Status (requires auth + admin)
```
GET /admin/api-status
GET /admin/api-status/circuit
POST /admin/api-status/circuit/{service}/{action}  # open/close
```

---

## Testing

### Run Tests
```bash
# Test specific API
php artisan api:test:ai
php artisan api:test:geocode
php artisan api:test:whatsapp 6281234567890

# Check all
php artisan api:health-check
```

### Verify Configuration
```bash
php artisan tinker
>>> config('services.fireworks.api_key');
>>> app(\App\Services\ExternalApi\FireworksService::class)->validateConnection();
```

---

## Troubleshooting

### Circuit Breaker Open
```bash
# Check status
php artisan api:circuit:status

# Reset if needed
php artisan api:circuit:close {service}
```

### Rate Limited
- Service akan otomatis retry dengan exponential backoff
- Cek queue: `php artisan tinker` -> `app(\App\Services\ExternalApi\ApiRateLimiter::class)->getQueueSize('whatsapp')`

### Cache Issues
```bash
# Clear all cache
php artisan cache:clear

# Clear specific service stale cache (via code)
app(\App\Services\ExternalApi\FallbackManager::class)->clearStaleCache('google_maps');
```

---

## Dependent Tasks

| Task | Uses Task 13 For |
|------|-----------------|
| Task 5 (AI) | FireworksService |
| Task 7 (Map) | GoogleMapsService, geocoding |
| Task 8 (Admin) | Geocoding untuk alamat |
| Task 9 (Notif) | WhatsAppService |
| Task 11 (Dashboard) | ApiStatusController |

---

## Implementasi Complete

Semua sub-task telah diimplementasikan:

- [x] 13.1 Configure Fireworks AI API credentials and endpoint
- [x] 13.2 Implement API client with timeout and retry logic
- [x] 13.3 Configure Google Maps JavaScript API key
- [x] 13.4 Set up Google Maps Geocoding API integration
- [x] 13.5 Configure WhatsApp Web API endpoint and authentication
- [x] 13.6 Implement circuit breaker pattern for external APIs
- [x] 13.7 Add API response caching where appropriate
- [x] 13.8 Create fallback mechanisms for API failures
- [x] 13.9 Implement API usage monitoring and alerting
- [x] 13.10 Add API rate limit handling
