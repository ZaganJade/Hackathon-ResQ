<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'whatsapp_number',
        'disaster_types',
        'is_active',
    ];

    protected $casts = [
        'disaster_types' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the notification preference.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active preferences.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if user is subscribed to a specific disaster type.
     */
    public function isSubscribedTo(string $disasterType): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // If disaster_types is null, user is subscribed to all types
        if ($this->disaster_types === null || empty($this->disaster_types)) {
            return true;
        }

        return in_array($disasterType, $this->disaster_types);
    }

    /**
     * Validate WhatsApp number format (Indonesian).
     */
    public static function validatePhoneNumber(string $number): bool
    {
        // Indonesian phone numbers: +62 or 62 followed by 9-12 digits
        // Or 08 followed by 9-11 digits
        $pattern = '/^(\+62|62|0)8[1-9][0-9]{8,11}$/';
        return preg_match($pattern, $number) === 1;
    }

    /**
     * Normalize phone number to international format.
     */
    public static function normalizePhoneNumber(string $number): string
    {
        // Remove all non-digit characters
        $number = preg_replace('/[^0-9]/', '', $number);

        // Convert 08xxx to 628xxx
        if (str_starts_with($number, '08')) {
            $number = '62' . substr($number, 1);
        }

        // Add + prefix
        if (!str_starts_with($number, '+')) {
            $number = '+' . $number;
        }

        return $number;
    }

    /**
     * Get all active preferences for a disaster type.
     */
    public static function getActiveForDisasterType(string $disasterType)
    {
        return self::active()
            ->get()
            ->filter(function ($preference) use ($disasterType) {
                return $preference->isSubscribedTo($disasterType);
            });
    }
}
