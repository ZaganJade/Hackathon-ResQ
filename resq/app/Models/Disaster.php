<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Disaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'location',
        'latitude',
        'longitude',
        'severity',
        'status',
        'description',
        'source',
        'source_id',
        'raw_data',
        'resolved_at',
    ];

    protected $casts = [
        'raw_data' => 'array',
        'resolved_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($disaster) {
            // Auto-classify severity based on type and magnitude if applicable
            if ($disaster->type === 'earthquake' && isset($disaster->raw_data['magnitude'])) {
                $magnitude = $disaster->raw_data['magnitude'];
                if ($magnitude >= 7.0) {
                    $disaster->severity = 'critical';
                } elseif ($magnitude >= 6.0) {
                    $disaster->severity = 'high';
                } elseif ($magnitude >= 5.0) {
                    $disaster->severity = 'medium';
                } else {
                    $disaster->severity = 'low';
                }
            }
        });
    }

    /**
     * Scope a query to only include disasters within a radius.
     * Supports both PostgreSQL (with trig functions) and SQLite (bounding box only).
     */
    public function scopeWithinRadius(Builder $query, float $lat, float $lng, float $radiusKm): Builder
    {
        $driver = config('database.default');

        // For PostgreSQL, use native SQL trig functions
        if ($driver === 'pgsql') {
            $earthRadius = 6371;
            return $query->whereRaw(
                "($earthRadius * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?",
                [$lat, $lng, $lat, $radiusKm]
            );
        }

        // For SQLite and others: use bounding box approximation only
        // Full distance calculation must be done in PHP
        // Approximate 1 degree = 111 km (simplified)
        $latDelta = $radiusKm / 111;
        $lngDelta = $radiusKm / (111 * cos(deg2rad($lat)));

        return $query
            ->whereBetween('latitude', [$lat - $latDelta, $lat + $latDelta])
            ->whereBetween('longitude', [$lng - $lngDelta, $lng + $lngDelta]);
    }

    /**
     * Calculate distance between two coordinates using Haversine formula.
     */
    public static function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    /**
     * Scope a query to only include active disasters.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to filter by severity.
     */
    public function scopeSeverity(Builder $query, string $severity): Builder
    {
        return $query->where('severity', $severity);
    }

    /**
     * Scope a query to filter by type.
     */
    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Get disasters as GeoJSON format.
     */
    public static function asGeoJson(): array
    {
        return [
            'type' => 'FeatureCollection',
            'features' => self::all()->map(function ($disaster) {
                return [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [$disaster->longitude, $disaster->latitude],
                    ],
                    'properties' => [
                        'id' => $disaster->id,
                        'type' => $disaster->type,
                        'location' => $disaster->location,
                        'severity' => $disaster->severity,
                        'status' => $disaster->status,
                        'description' => $disaster->description,
                        'created_at' => $disaster->created_at,
                    ],
                ];
            })->toArray(),
        ];
    }
}
