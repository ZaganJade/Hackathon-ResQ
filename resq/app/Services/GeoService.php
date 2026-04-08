<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeoService
{
    private ?string $googleApiKey;
    private string $googleBaseUrl = 'https://maps.googleapis.com/maps/api';

    public function __construct()
    {
        $this->googleApiKey = config('services.google.maps_api_key', null);
    }

    /**
     * Geocode a location name to coordinates using free Nominatim (OSM) first,
     * fallback to Google if configured.
     *
     * @param string $location Location name (e.g., "Jakarta, Indonesia")
     * @return array|null ['lat' => float, 'lng' => float, 'formatted_address' => string] or null on failure
     */
    public function geocode(string $location): ?array
    {
        // Try Nominatim (OpenStreetMap) first - 100% free, no API key needed
        $nominatimResult = $this->geocodeWithNominatim($location);
        if ($nominatimResult) {
            return $nominatimResult;
        }

        // Fallback to Google if API key is configured
        if (!empty($this->googleApiKey)) {
            return $this->geocodeWithGoogle($location);
        }

        Log::warning('Geocoding failed for location: ' . $location);
        return null;
    }

    /**
     * Geocode using Nominatim (OpenStreetMap) - Free, no API key required
     *
     * @param string $location
     * @return array|null
     */
    private function geocodeWithNominatim(string $location): ?array
    {
        try {
            // Add Indonesia bias if not already present
            $searchQuery = $location;
            if (!str_contains(strtolower($location), 'indonesia')) {
                $searchQuery .= ', Indonesia';
            }

            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'ResQ/1.0 (ResQ Disaster Management App)',
                    'Accept' => 'application/json',
                ])
                ->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $searchQuery,
                    'format' => 'json',
                    'limit' => 1,
                    'accept-language' => 'id',
                ]);

            if ($response->successful()) {
                $results = $response->json();

                if (!empty($results) && is_array($results)) {
                    $result = $results[0];

                    return [
                        'lat' => (float) $result['lat'],
                        'lng' => (float) $result['lon'],
                        'formatted_address' => $result['display_name'],
                        'source' => 'nominatim',
                    ];
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::warning('Nominatim geocoding failed', [
                'location' => $location,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Geocode using Google Maps API (requires API key)
     *
     * @param string $location
     * @return array|null
     */
    private function geocodeWithGoogle(string $location): ?array
    {
        if (empty($this->googleApiKey)) {
            return null;
        }

        try {
            $response = Http::timeout(10)
                ->get("{$this->googleBaseUrl}/geocode/json", [
                    'address' => $location,
                    'key' => $this->googleApiKey,
                    'region' => 'id', // Indonesia region bias
                ]);

            if ($response->successful() && $response->json('status') === 'OK') {
                $result = $response->json('results.0');

                return [
                    'lat' => $result['geometry']['location']['lat'],
                    'lng' => $result['geometry']['location']['lng'],
                    'formatted_address' => $result['formatted_address'],
                    'source' => 'google',
                ];
            }

            Log::warning('Google geocoding failed', [
                'location' => $location,
                'status' => $response->json('status'),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Google geocoding error', [
                'location' => $location,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Reverse geocode coordinates to address using Nominatim (free) or Google (fallback).
     *
     * @param float $lat Latitude
     * @param float $lng Longitude
     * @return string|null Formatted address or null on failure
     */
    public function reverseGeocode(float $lat, float $lng): ?string
    {
        // Try Nominatim first (free)
        $nominatimResult = $this->reverseGeocodeWithNominatim($lat, $lng);
        if ($nominatimResult) {
            return $nominatimResult;
        }

        // Fallback to Google if configured
        if (!empty($this->googleApiKey)) {
            return $this->reverseGeocodeWithGoogle($lat, $lng);
        }

        return null;
    }

    /**
     * Reverse geocode using Nominatim (OpenStreetMap) - Free
     *
     * @param float $lat
     * @param float $lng
     * @return string|null
     */
    private function reverseGeocodeWithNominatim(float $lat, float $lng): ?string
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'ResQ/1.0 (ResQ Disaster Management App)',
                    'Accept' => 'application/json',
                ])
                ->get('https://nominatim.openstreetmap.org/reverse', [
                    'lat' => $lat,
                    'lon' => $lng,
                    'format' => 'json',
                    'accept-language' => 'id',
                ]);

            if ($response->successful()) {
                $result = $response->json();

                if (isset($result['display_name'])) {
                    return $result['display_name'];
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::warning('Nominatim reverse geocoding failed', [
                'lat' => $lat,
                'lng' => $lng,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Reverse geocode using Google Maps API
     *
     * @param float $lat
     * @param float $lng
     * @return string|null
     */
    private function reverseGeocodeWithGoogle(float $lat, float $lng): ?string
    {
        if (empty($this->googleApiKey)) {
            return null;
        }

        try {
            $response = Http::timeout(10)
                ->get("{$this->googleBaseUrl}/geocode/json", [
                    'latlng' => "{$lat},{$lng}",
                    'key' => $this->googleApiKey,
                ]);

            if ($response->successful() && $response->json('status') === 'OK') {
                return $response->json('results.0.formatted_address');
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Google reverse geocoding error', [
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
     * @return array Configuration array
     */
    public function getMapConfig(): array
    {
        return [
            'provider' => 'leaflet', // Now uses Leaflet + OpenStreetMap
            'osm_tiles' => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            'center' => [
                'lat' => -2.5489, // Center of Indonesia
                'lng' => 118.0149,
            ],
            'zoom' => 5,
            'max_zoom' => 18,
            'min_zoom' => 4,
            'attribution' => '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
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
            'critical' => '#f43f5e', // Rose-500 (updated for Tailwind v4)
            'high' => '#f43f5e',     // Rose-500
            'medium' => '#f59e0b',   // Amber-500
            'low' => '#059669',      // Emerald-600
            default => '#6B7280',    // Gray-500
        };
    }

    /**
     * Get severity-based marker icon URL.
     * Note: For Leaflet, we use CSS-styled markers instead of image icons.
     * This method kept for backwards compatibility.
     *
     * @param string $severity Severity level
     * @return string CSS color value
     */
    public function getSeverityMarkerIcon(string $severity): string
    {
        // For Leaflet circle markers, we return the color directly
        return $this->getSeverityColor($severity);
    }
}
