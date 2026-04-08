<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Guide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'content',
        'steps',
        'image',
        'video_url',
        'status',
    ];

    protected $casts = [
        'steps' => 'array',
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::creating(function ($guide) {
            if (empty($guide->slug)) {
                $guide->slug = Str::slug($guide->title);
            }
        });

        static::updating(function ($guide) {
            if ($guide->isDirty('title') && empty($guide->slug)) {
                $guide->slug = Str::slug($guide->title);
            }
        });
    }

    /**
     * Scope a query to only include published guides.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get formatted steps.
     */
    public function getFormattedSteps(): array
    {
        if (is_string($this->steps)) {
            return json_decode($this->steps, true) ?? [];
        }
        return $this->steps ?? [];
    }

    /**
     * Get guides by category grouped.
     */
    public static function getByCategory(): array
    {
        return self::published()
            ->get()
            ->groupBy('category')
            ->toArray();
    }
}
