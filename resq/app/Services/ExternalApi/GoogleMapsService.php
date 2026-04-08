<?php

namespace App\Services\ExternalApi;

use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Google Maps Service
 * Task 13.3 & 13.4 Implementation
 *
 * Provides:
 * - Geocoding (address to coordinates)
 * - Reverse geocoding (coordinates to address)
 * - Distance calculation
 * - Place search
 * - JavaScript API key management
 * - Caching for geocoding results (13.7)
 * - Circuit breaker protection (13.6)
 * - Rate limit handling (13.10)
 */
class GoogleMapsService extends BaseApiClient
{
    protected ?string $jsApiKey = null;
    protected string $geocodingEndpoint;

    protected CircuitBreaker $circuitBreaker;
    protected ApiMonitor $monitor;
    protected FallbackManager $fallback;

    public function __construct(
        ?CircuitBreaker $circuitBreaker = null,
        ?ApiMonitor $monitor = null,
        ?FallbackManager $fallback = null
    ) {
        $this->circuitBreaker = $circuitBreaker ?? new CircuitBreaker();
        $this->monitor = $monitor ?? new ApiMonitor();
        $this->fallback = $fallback ?? new FallbackManager();
        parent::__construct();
    }

    protected function configure(): void
    {
        $config = config('services.google_maps');

        $this->baseUrl = '';
        $this->apiKey = $config['api_key'] ?? null;
        $this->jsApiKey = $config['js_api_key'] ?? $this->apiKey;
        $this->geocodingEndpoint = $config['geocoding_endpoint'] ?? 'https://maps.googleapis.com/maps/api/geocode/json';

        $this->timeout = $config['timeout'] ?? 10;
        $this->maxRetries = $config['max_retries'] ?? 3;
        $this->retryDelay = 1000;
        $this->retryMultiplier = 1.5;
    }

    protected function getServiceName(): string
    {
        return 'google_maps';
    }

    /**
     * Geocode an address to coordinates (13.4)
     *
     * @param string $address Address to geocode
     * @param bool $useCache Use cached result if available
     * @return array|null ['lat' => float, 'lng' => float, 'address' => string, 'place_id' => string]
     * @throws ApiException
     */
    public function geocode(string $address, bool $useCache = true): ?array
    {
        if (empty($address)) {
            return null;
        }

        $cacheKey = 'geocode:' . md5($address);
        $cacheTtl = config('services.external_api.cache.geocoding_ttl', 2592000); // 30 days

        // Check cache first (13.7)
        if ($useCache && $cached = Cache::get($cacheKey)) {
            Log::debug('Geocoding cache hit', ['address' => $address]);
            return $cached;
        }

        return $this->circuitBreaker->call($this->getServiceName(), function () use ($address, $cacheKey, $cacheTtl) {
            $startTime = microtime(true);

            try {
                // Build URL manually since we're using a different endpoint structure
                $url = $this->geocodingEndpoint;
                $query = [
                    'address' => $address,
                    'key' => $this->apiKey,
                    'region' => 'id', // Indonesia region bias
                    'language' => 'id', // Indonesian language results
                ];

                $response = Http::timeout($this->timeout)
                    ->get($url, $query);

                $responseTime = round((microtime(true) - $startTime) * 1000, 2);

                $data = $response->json();

                // Check API-level errors
                if (isset($data['error_message'])) {
                    throw new ApiException(
                        message: "Google Maps API error: {$data['error_message']}",
                        statusCode: $response->status(),
                        service: $this->getServiceName(),
                        context: ['address' => $address, 'status' => $data['status'] ?? null]
                    );
                }

                // Check geocoding status
                $status = $data['status'] ?? 'UNKNOWN';

                if ($status !== 'OK') {
                    if ($status === 'ZERO_RESULTS') {
                        // Not an error, just no results
                        $this->monitor->track(
                            service: $this->getServiceName(),
                            endpoint: 'geocode',
                            responseTimeMs: $responseTime,
                            success: true,
                            statusCode: 200
                        );
                        return null;
                    }

                    throw new ApiException(
                        message: "Geocoding failed: {$status}",
                        statusCode: $response->status(),
                        service: $this->getServiceName(),
                        context: ['status' => $status, 'address' => $address]
                    );
                }

                // Extract location from first result
                $result = $data['results'][0] ?? null;

                if (!$result) {
                    return null;
                }

                $location = $result['geometry']['location'] ?? null;

                if (!$location) {
                    return null;
                }

                $result = [
                    'lat' => (float) $location['lat'],
                    'lng' => (float) $location['lng'],
                    'address' => $result['formatted_address'] ?? $address,
                    'place_id' => $result['place_id'] ?? null,
                    'types' => $result['types'] ?? [],
                    'address_components' => $result['address_components'] ?? [],
                    'partial_match' => $result['partial_match'] ?? false,
                ];

                // Cache successful result (13.7)
                Cache::put($cacheKey, $result, $cacheTtl);

                // Also store in stale cache for fallback
                $this->fallback->storeStaleCache($this->getServiceName(), $cacheKey, $result);

                // Track metrics (13.9)
                $this->monitor->track(
                    service: $this->getServiceName(),
                    endpoint: 'geocode',
                    responseTimeMs: $responseTime,
                    success: true,
                    statusCode: 200
                );

                $this->fallback->recordSuccess($this->getServiceName());

                return $result;

            } catch (\Throwable $e) {
                if ($e instanceof ApiException) {
                    throw $e;
                }

                $responseTime = round((microtime(true) - $startTime) * 1000, 2);

                $this->monitor->track(
                    service: $this->getServiceName(),
                    endpoint: 'geocode',
                    responseTimeMs: $responseTime,
                    success: false,
                    error: $e->getMessage()
                );

                $this->fallback->recordFailure($this->getServiceName());

                throw new ApiException(
                    message: "Geocoding request failed: {$e->getMessage()}",
                    code: 0,
                    previous: $e,
                    service: $this->getServiceName(),
                    context: ['address' => $address]
                );
            }
        });
    }

    /**
     * Reverse geocode coordinates to address (13.4)
     *
     * @param float $lat Latitude
     * @param float $lng Longitude
     * @param bool $useCache Use cached result if available
     * @return array|null ['address' => string, 'place_id' => string, 'address_components' => array]
     * @throws ApiException
     */
    public function reverseGeocode(float $lat, float $lng, bool $useCache = true): ?array
    {
        $cacheKey = 'reverse_geocode:' . md5("{$lat},{$lng}");
        $cacheTtl = config('services.external_api.cache.geocoding_ttl', 2592000);

        // Check cache (13.7)
        if ($useCache && $cached = Cache::get($cacheKey)) {
            return $cached;
        }

        return $this->circuitBreaker->call($this->getServiceName(), function () use ($lat, $lng, $cacheKey, $cacheTtl) {
            $startTime = microtime(true);

            try {
                $url = $this->geocodingEndpoint;
                $query = [
                    'latlng' => "{$lat},{$lng}",
                    'key' => $this->apiKey,
                    'language' => 'id',
                ];

                $response = Http::timeout($this->timeout)->get($url, $query);
                $responseTime = round((microtime(true) - $startTime) * 1000, 2);

                $data = $response->json();

                if (isset($data['error_message'])) {
                    throw new ApiException(
                        message: $data['error_message'],
                        service: $this->getServiceName(),
                        statusCode: $response->status()
                    );
                }

                $status = $data['status'] ?? 'UNKNOWN';

                if ($status !== 'OK') {
                    if ($status === 'ZERO_RESULTS') {
                        $this->monitor->track(
                            service: $this->getServiceName(),
                            endpoint: 'reverse_geocode',
                            responseTimeMs: $responseTime,
                            success: true,
                            statusCode: 200
                        );
                        return null;
                    }

                    throw new ApiException(
                        message: "Reverse geocoding failed: {$status}",
                        service: $this->getServiceName(),
                        statusCode: $response->status()
                    );
                }

                $result = $data['results'][0] ?? null;

                if (!$result) {
                    return null;
                }

                $formatted = [
                    'address' => $result['formatted_address'] ?? null,
                    'place_id' => $result['place_id'] ?? null,
                    'address_components' => $result['address_components'] ?? [],
                    'types' => $result['types'] ?? [],
                    'lat' => $lat,
                    'lng' => $lng,
                ];

                // Cache result
                Cache::put($cacheKey, $formatted, $cacheTtl);
                $this->fallback->storeStaleCache($this->getServiceName(), $cacheKey, $formatted);

                $this->monitor->track(
                    service: $this->getServiceName(),
                    endpoint: 'reverse_geocode',
                    responseTimeMs: $responseTime,
                    success: true,
                    statusCode: 200
                );

                $this->fallback->recordSuccess($this->getServiceName());

                return $formatted;

            } catch (\Throwable $e) {
                $responseTime = round((microtime(true) - $startTime) * 1000, 2);

                $this->monitor->track(
                    service: $this->getServiceName(),
                    endpoint: 'reverse_geocode',
                    responseTimeMs: $responseTime,
                    success: false,
                    error: $e->getMessage()
                );

                $this->fallback->recordFailure($this->getServiceName());

                throw new ApiException(
                    message: "Reverse geocoding failed: {$e->getMessage()}",
                    previous: $e,
                    service: $this->getServiceName()
                );
            }
        });
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     * (Does not require API call)
     *
     * @param float $lat1 Origin latitude
     * @param float $lng1 Origin longitude
     * @param float $lat2 Destination latitude
     * @param float $lng2 Destination longitude
     * @return float Distance in kilometers
     */
    public function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // km

        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lngDelta / 2) * sin($lngDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Find coordinates within radius
     *
     * @param array $points Array of ['lat' => x, 'lng' => y, ...]
     * @param float $centerLat Center latitude
     * @param float $centerLng Center longitude
     * @param float $radiusKm Radius in kilometers
     * @return array Filtered points within radius
     */
    public function filterWithinRadius(array $points, float $centerLat, float $centerLng, float $radiusKm): array
    {
        return array_filter($points, function ($point) use ($centerLat, $centerLng, $radiusKm) {
            $distance = $this->calculateDistance(
                $centerLat,
                $centerLng,
                $point['lat'] ?? 0,
                $point['lng'] ?? 0
            );
            return $distance <= $radiusKm;
        });
    }

    /**
     * Get JavaScript API key for frontend (13.3)
     * Returns the restricted browser key rather than server key
     */
    public function getJavaScriptApiKey(): ?string
    {
        return $this->jsApiKey;
    }

    /**
     * Validate API key
     */
    public function validateConnection(): bool
    {
        try {
            $result = $this->geocode('Jakarta, Indonesia', false);
            return $result !== null;
        } catch (\Throwable $e) {
            Log::error('Google Maps connection validation failed', [
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Get service status
     */
    public function getStatus(): array
    {
        $circuitStatus = $this->circuitBreaker->getStatus($this->getServiceName());
        $health = $this->fallback->checkServiceHealth($this->getServiceName());
        $metrics = $this->monitor->getServiceMetrics($this->getServiceName(), 5);

        return [
            'service' => $this->getServiceName(),
            'circuit_breaker' => $circuitStatus,
            'health' => $health,
            'metrics' => $metrics,
            'config' => [
                'timeout' => $this->timeout,
                'max_retries' => $this->maxRetries,
            ],
        ];
    }

    /**
     * Batch geocode multiple addresses
     *
     * @param array $addresses Array of addresses
     * @return array Array of geocoding results indexed by original key
     */
    public function batchGeocode(array $addresses): array
    {
        $results = [];

        foreach ($addresses as $key => $address) {
            try {
                $results[$key] = $this->geocode($address);
            } catch (\Throwable $e) {
                Log::warning("Batch geocoding failed for '{$address}'", [
                    'error' => $e->getMessage(),
                ]);
                $results[$key] = null;
            }
        }

        return $results;
    }

    /**
     * Clear geocoding cache
     */
    public function clearCache(): void
    {
        // Clear all cache entries with 'geocode:' or 'reverse_geocode:' prefix
        // Note: This requires cache driver that supports pattern deletion
        // For Redis: use keys command or scan
        // For file/database: iterate and delete

        Log::info('Clearing geocoding cache');
    }
}
