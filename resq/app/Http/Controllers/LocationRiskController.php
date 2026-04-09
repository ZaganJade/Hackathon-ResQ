<?php

namespace App\Http\Controllers;

use App\Services\GeoService;
use App\Services\LocationRiskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LocationRiskController extends Controller
{
    private LocationRiskService $locationRisk;
    private GeoService $geoService;

    public function __construct(LocationRiskService $locationRisk, GeoService $geoService)
    {
        $this->locationRisk = $locationRisk;
        $this->geoService = $geoService;
    }

    /**
     * Analyze zone status based on user location coordinates
     * POST /api/v1/location/analyze
     */
    public function analyze(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_km' => 'nullable|numeric|min:1|max:200',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Koordinat lokasi tidak valid',
                'errors' => $validator->errors(),
            ], 422);
        }

        $lat = $request->input('latitude');
        $lng = $request->input('longitude');
        $radius = $request->input('radius_km');

        try {
            $startTime = microtime(true);

            $analysis = $this->locationRisk->analyzeZoneStatus($lat, $lng, $radius);
            $trend = $this->locationRisk->getRiskTrend($lat, $lng);

            $endTime = microtime(true);

            // Get address from coordinates if possible
            $address = $this->geoService->reverseGeocode($lat, $lng);

            return response()->json([
                'success' => true,
                'data' => [
                    'zone' => [
                        'status' => $analysis['status'],
                        'label' => $analysis['status_label'],
                        'color' => $analysis['status_color'],
                    ],
                    'location' => [
                        'address' => $address,
                        'coordinates' => [
                            'latitude' => $lat,
                            'longitude' => $lng,
                        ],
                        'radius_km' => $analysis['location']['radius_km'],
                    ],
                    'metrics' => $analysis['metrics'],
                    'trend' => $trend,
                    'disasters_by_type' => $analysis['disasters_by_type'],
                    'disasters_by_severity' => $analysis['disasters_by_severity'],
                    'most_recent' => $analysis['most_recent_disaster'],
                    'warning' => $analysis['warning_message'],
                    'recommendations' => $analysis['recommendations'],
                    'nearby_disasters' => $analysis['nearby_disasters'],
                ],
                'meta' => [
                    'processed_at' => $analysis['analyzed_at'],
                    'response_time_ms' => round(($endTime - $startTime) * 1000, 2),
                ],
            ]);

        } catch (\Throwable $e) {
            Log::error('Location analysis failed', [
                'lat' => $lat,
                'lng' => $lng,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Gagal menganalisis status zona',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Quick zone status check (lightweight)
     * GET /api/v1/location/status?lat={}&lng={}
     */
    public function quickStatus(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Koordinat tidak valid',
            ], 422);
        }

        $lat = $request->input('lat');
        $lng = $request->input('lng');

        try {
            $status = $this->locationRisk->quickZoneStatus($lat, $lng);
            $trend = $this->locationRisk->getRiskTrend($lat, $lng);

            return response()->json([
                'success' => true,
                'data' => [
                    'status' => $status['status'],
                    'label' => $status['label'],
                    'color' => $status['color'],
                    'risk_score' => $status['risk_score'],
                    'total_disasters' => $status['total_disasters'],
                    'trend' => $trend['trend'],
                    'trend_change_percent' => $trend['change_percent'],
                ],
            ]);

        } catch (\Throwable $e) {
            Log::error('Quick zone status failed', [
                'lat' => $lat,
                'lng' => $lng,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Gagal memeriksa status zona',
            ], 500);
        }
    }

    /**
     * Chat with location-aware AI
     * POST /api/v1/location/chat
     */
    public function chat(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:2000',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'conversation_id' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Data tidak valid',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'Autentikasi diperlukan',
            ], 401);
        }

        $message = strip_tags($request->input('message'));
        $lat = $request->input('latitude');
        $lng = $request->input('longitude');
        $conversationId = $request->input('conversation_id');

        try {
            // Use AIAssistService with location
            $aiService = app(\App\Services\AIAssistService::class);
            $result = $aiService->chatWithLocation($message, $user->id, $lat, $lng, $conversationId);

            return response()->json([
                'success' => $result['success'],
                'reply' => $result['reply'],
                'conversation_id' => $result['conversation_id'],
                'response_time' => $result['response_time'],
                'location_context' => $result['location_context'] ?? null,
            ]);

        } catch (\Throwable $e) {
            Log::error('Location-aware chat failed', [
                'user_id' => $user->id,
                'message' => $message,
                'lat' => $lat,
                'lng' => $lng,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Gagal memproses pesan dengan konteks lokasi',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get disasters near a location
     * GET /api/v1/location/nearby-disasters?lat={}&lng={}&radius={}
     */
    public function nearbyDisasters(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:1|max:200',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Koordinat tidak valid',
            ], 422);
        }

        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $radius = $request->input('radius', 50);

        try {
            $disasters = \App\Models\Disaster::active()
                ->withinRadius($lat, $lng, $radius)
                ->orderBy('created_at', 'desc')
                ->get();

            $formatted = $disasters->map(function ($disaster) use ($lat, $lng) {
                $distance = $this->calculateDistance($lat, $lng, $disaster->latitude, $disaster->longitude);

                return [
                    'id' => $disaster->id,
                    'type' => $disaster->type,
                    'severity' => $disaster->severity,
                    'location' => $disaster->location,
                    'coordinates' => [
                        'latitude' => $disaster->latitude,
                        'longitude' => $disaster->longitude,
                    ],
                    'description' => $disaster->description,
                    'created_at' => $disaster->created_at->toIso8601String(),
                    'distance_km' => round($distance, 2),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $formatted->count(),
                    'center' => ['lat' => $lat, 'lng' => $lng],
                    'radius_km' => $radius,
                    'disasters' => $formatted,
                ],
            ]);

        } catch (\Throwable $e) {
            Log::error('Nearby disasters query failed', [
                'lat' => $lat,
                'lng' => $lng,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Gagal mengambil data bencana',
            ], 500);
        }
    }

    /**
     * Get location info from coordinates
     * GET /api/v1/location/reverse-geocode?lat={}&lng={}
     */
    public function reverseGeocode(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Koordinat tidak valid',
            ], 422);
        }

        $lat = $request->input('lat');
        $lng = $request->input('lng');

        try {
            $address = $this->geoService->reverseGeocode($lat, $lng);

            if (!$address) {
                return response()->json([
                    'success' => false,
                    'error' => 'Alamat tidak ditemukan untuk koordinat tersebut',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'address' => $address,
                    'coordinates' => [
                        'latitude' => $lat,
                        'longitude' => $lng,
                    ],
                ],
            ]);

        } catch (\Throwable $e) {
            Log::error('Reverse geocoding failed', [
                'lat' => $lat,
                'lng' => $lng,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Gagal mendapatkan informasi lokasi',
            ], 500);
        }
    }

    /**
     * Calculate distance between two points
     */
    private function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
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
}
