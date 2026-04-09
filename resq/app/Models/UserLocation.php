<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'latitude',
        'longitude',
        'address',
        'is_default',
        'notifications_enabled',
        'notification_radius_km',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_default' => 'boolean',
        'notifications_enabled' => 'boolean',
    ];

    /**
     * Get the user that owns this location.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get default location.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope to get locations with notifications enabled.
     */
    public function scopeNotificationsEnabled($query)
    {
        return $query->where('notifications_enabled', true);
    }

    /**
     * Ensure only one default location per user.
     */
    protected static function booted(): void
    {
        static::saving(function ($location) {
            if ($location->is_default) {
                // Remove default flag from other locations
                static::where('user_id', $location->user_id)
                    ->where('id', '!=', $location->id)
                    ->update(['is_default' => false]);
            }
        });
    }
}
