<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * API Metric Model for Task 13.9
 * Stores metrics for external API usage monitoring
 */
class ApiMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'service',
        'endpoint',
        'response_time_ms',
        'success',
        'status_code',
        'error_message',
    ];

    protected $casts = [
        'success' => 'boolean',
        'response_time_ms' => 'float',
        'status_code' => 'integer',
        'created_at' => 'datetime',
    ];

    public $timestamps = false; // Only created_at needed

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = $model->created_at ?? now();
        });
    }

    /**
     * Scope for specific service
     */
    public function scopeForService($query, string $service)
    {
        return $query->where('service', $service);
    }

    /**
     * Scope for time window
     */
    public function scopeSince($query, $time)
    {
        return $query->where('created_at', '>=', $time);
    }

    /**
     * Scope for successful requests
     */
    public function scopeSuccessful($query)
    {
        return $query->where('success', true);
    }

    /**
     * Scope for failed requests
     */
    public function scopeFailed($query)
    {
        return $query->where('success', false);
    }
}
