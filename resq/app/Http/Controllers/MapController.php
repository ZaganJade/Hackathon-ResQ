<?php

namespace App\Http\Controllers;

use App\Models\Disaster;
use App\Services\GeoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MapController extends Controller
{
    private GeoService $geoService;

    public function __construct(GeoService $geoService)
    {
        $this->geoService = $geoService;
    }

    /**
     * Display the disaster map page.
     */
    public function index(): View
    {
        $mapConfig = $this->geoService->getMapConfig();

        // Get unique disaster types for filter
        $disasterTypes = Disaster::distinct()->pluck('type')->sort()->values();

        return view('map.index', [
            'mapConfig' => $mapConfig,
            'disasterTypes' => $disasterTypes,
            'googleMapsApiKey' => config('services.google.maps_api_key', ''),
        ]);
    }

    /**
     * Get disasters in GeoJSON format.
     */
    public function getDisasters(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'types' => 'nullable|array',
            'types.*' => 'string',
            'severity' => 'nullable|array',
            'severity.*' => 'string|in:low,medium,high,critical',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:1|max:500',
        ]);

        $query = Disaster::query();

        // Filter by types
        if (!empty($validated['types'])) {
            $query->whereIn('type', $validated['types']);
        }

        // Filter by severity (now supports multiple like types)
        if (!empty($validated['severity'])) {
            $query->whereIn('severity', $validated['severity']);
        }

        // Filter by date range
        if (!empty($validated['date_from'])) {
            $query->where('created_at', '>=', $validated['date_from']);
        }
        if (!empty($validated['date_to'])) {
            $query->where('created_at', '<=', $validated['date_to'] . ' 23:59:59');
        }

        // Filter by radius if location provided
        if (!empty($validated['lat']) && !empty($validated['lng'])) {
            $radius = $validated['radius'] ?? 50; // Default 50km
            $query->withinRadius($validated['lat'], $validated['lng'], $radius);
        }

        $disasters = $query->orderBy('created_at', 'desc')->get();

        // Convert to GeoJSON format
        $features = $disasters->map(function ($disaster) {
            return [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        (float) $disaster->longitude,
                        (float) $disaster->latitude,
                    ],
                ],
                'properties' => [
                    'id' => $disaster->id,
                    'type' => $disaster->type,
                    'location' => $disaster->location,
                    'severity' => $disaster->severity,
                    'status' => $disaster->status,
                    'description' => $disaster->description,
                    'color' => $this->geoService->getSeverityColor($disaster->severity),
                    'created_at' => $disaster->created_at?->toIso8601String(),
                    'updated_at' => $disaster->updated_at?->toIso8601String(),
                ],
            ];
        });

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
            'meta' => [
                'total' => $disasters->count(),
                'filters' => [
                    'types' => $validated['types'] ?? null,
                    'severity' => $validated['severity'] ?? null,
                    'date_from' => $validated['date_from'] ?? null,
                    'date_to' => $validated['date_to'] ?? null,
                    'radius' => $validated['radius'] ?? null,
                ],
            ],
        ]);
    }

    /**
     * Search for a location and return coordinates.
     */
    public function geocode(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'location' => 'required|string|min:2|max:255',
        ]);

        $result = $this->geoService->geocode($validated['location']);

        if ($result === null) {
            return response()->json([
                'error' => 'Location not found',
            ], 404);
        }

        // Get disasters within 50km of this location
        $nearbyDisasters = Disaster::withinRadius($result['lat'], $result['lng'], 50)
            ->orderBy('created_at', 'desc')
            ->limit(100)
            ->get();

        return response()->json([
            'location' => $result,
            'nearby_disasters' => [
                'count' => $nearbyDisasters->count(),
                'radius_km' => 50,
            ],
        ]);
    }

    /**
     * Get a single disaster details.
     */
    public function show(Disaster $disaster): JsonResponse
    {
        return response()->json([
            'id' => $disaster->id,
            'type' => $disaster->type,
            'location' => $disaster->location,
            'latitude' => $disaster->latitude,
            'longitude' => $disaster->longitude,
            'severity' => $disaster->severity,
            'status' => $disaster->status,
            'description' => $disaster->description,
            'source' => $disaster->source,
            'source_id' => $disaster->source_id,
            'raw_data' => $disaster->raw_data,
            'color' => $this->geoService->getSeverityColor($disaster->severity),
            'created_at' => $disaster->created_at?->toIso8601String(),
            'updated_at' => $disaster->updated_at?->toIso8601String(),
            'resolved_at' => $disaster->resolved_at?->toIso8601String(),
        ]);
    }

    /**
     * Get available disaster types and their counts.
     */
    public function getStats(): JsonResponse
    {
        $stats = Disaster::selectRaw('type, severity, COUNT(*) as count')
            ->groupBy('type', 'severity')
            ->get()
            ->groupBy('type')
            ->map(function ($group) {
                return [
                    'total' => $group->sum('count'),
                    'by_severity' => $group->pluck('count', 'severity'),
                ];
            });

        $totalBySeverity = Disaster::selectRaw('severity, COUNT(*) as count')
            ->groupBy('severity')
            ->pluck('count', 'severity');

        return response()->json([
            'by_type' => $stats,
            'by_severity' => $totalBySeverity,
            'total' => Disaster::count(),
        ]);
    }
}
