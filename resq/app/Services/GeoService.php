<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeoService
{
    private string $apiKey;
    private string $baseUrl = 'https://maps.googleapis.com/maps/api';

    public function __construct()
    {
        $this->apiKey = config('services.google.maps_api_key', '');
    }

    /**
     * Geocode a location name to coordinates.
     *
     * @param string $location Location name (e.g., "Jakarta, Indonesia")
     * @return array|null ['lat' => float, 'lng' => float, 'formatted_address' => string] or null on failure
     */
    public function geocode(string $location): ?array
    {
        if (empty($this->apiKey)) {
            Log::warning('Google Maps API key not configured');
            return null;
        }

        try {
            $response = Http::timeout(10)
                ->get("{$this->baseUrl}/geocode/json", [
                    'address' => $location,
                    'key' => $this->apiKey,
                    'region' => 'id', // Indonesia region bias
                ]);

            if ($response->successful() && $response->json('status') === 'OK') {
                $result = $response->json('results.0');

                return [
                    'lat' => $result['geometry']['location']['lat'],
                    'lng' => $result['geometry']['location']['lng'],
                    'formatted_address' => $result['formatted_address'],
                ];
            }

            Log::warning('Geocoding failed', [
                'location' => $location,
                'status' => $response->json('status'),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Geocoding error', [
                'location' => $location,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Reverse geocode coordinates to address.
     *
     * @param float $lat Latitude
     * @param float $lng Longitude
     * @return string|null Formatted address or null on failure
     */
    public function reverseGeocode(float $lat, float $lng): ?string
    {
        if (empty($this->apiKey)) {
            Log::warning('Google Maps API key not configured');
            return null;
        }

        try {
            $response = Http::timeout(10)
                ->get("{$this->baseUrl}/geocode/json", [
                    'latlng' => "{$lat},{$lng}",
                    'key' => $this->apiKey,
                ]);

            if ($response->successful() && $response->json('status') === 'OK') {
                return $response->json('results.0.formatted_address');
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Reverse geocoding error', [
                'lat' => $lat,
                'lng' => $lng,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Calculate distance between two points using Haversine formula.
     *
     * @param float $lat1 Latitude of point 1
     * @param float $lng1 Longitude of point 1
     * @param float $lat2 Latitude of point 2
     * @param float $lng2 Longitude of point 2
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
     * Calculate bounding box for a radius search.
     *
     * @param float $lat Center latitude
     * @param float $lng Center longitude
     * @param float $radiusKm Radius in kilometers
     * @return array ['min_lat', 'max_lat', 'min_lng', 'max_lng']
     */
    public function getBoundingBox(float $lat, float $lng, float $radiusKm): array
    {
        $earthRadius = 6371; // km

        // Calculate latitude bounds (1 degree lat ≈ 111 km)
        $latDelta = rad2deg($radiusKm / $earthRadius);

        // Calculate longitude bounds (varies by latitude)
        $lngDelta = rad2deg($radiusKm / ($earthRadius * cos(deg2rad($lat))));

        return [
            'min_lat' => $lat - $latDelta,
            'max_lat' => $lat + $latDelta,
            'min_lng' => $lng - $lngDelta,
            'max_lng' => $lng + $lngDelta,
        ];
    }

    /**
     * Get map configuration for frontend.
     *
     * @return array Configuration array with API key, center, zoom
     */
    public function getMapConfig(): array
    {
        return [
            'api_key' => $this->apiKey,
            'center' => [
                'lat' => -2.5489, // Center of Indonesia
                'lng' => 118.0149,
            ],
            'zoom' => 5,
            'max_zoom' => 18,
            'min_zoom' => 4,
        ];
    }

    /**
     * Get severity-based marker color.
     *
     * @param string $severity Severity level (low, medium, high, critical)
     * @return string Hex color code
     */
    public function getSeverityColor(string $severity): string
    {
        return match (strtolower($severity)) {
            'critical' => '#DC2626', // Red-600
            'high' => '#DC2626',     // Red-600
            'medium' => '#F59E0B',   // Amber-500
            'low' => '#10B981',      // Emerald-500
            default => '#6B7280',    // Gray-500
        };
    }

    /**
     * Get severity-based marker icon URL.
     *
     * @param string $severity Severity level
     * @return string URL to marker icon
     */
    public function getSeverityMarkerIcon(string $severity): string
    {
        $color = $this->getSeverityColor($severity);

        // Use Google Charts API to generate marker icons
        // Replace # with 0x for the API
        $chartColor = str_replace('#', '0x', substr($color, 1));

        return "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=|{$chartColor}|000000";
    }
}
