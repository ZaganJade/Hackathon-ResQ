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
     */
    public function scopeWithinRadius(Builder $query, float $lat, float $lng, float $radiusKm): Builder
    {
        // Using Haversine formula approximation for PostgreSQL
        $earthRadius = 6371; // km

        return $query->whereRaw(
            "($earthRadius * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) <= ?",
            [$lat, $lng, $lat, $radiusKm]
        );
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
