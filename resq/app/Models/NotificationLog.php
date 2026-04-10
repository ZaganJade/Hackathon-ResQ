<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationLog extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_SENT = 'sent';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_FAILED = 'failed';
    public const STATUS_RETRYING = 'retrying';

    protected $fillable = [
        'user_id',
        'phone_number',
        'message',
        'status',
        'error_code',
        'retry_count',
        'sent_at',
        'delivered_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'retry_count' => 'integer',
    ];

    /**
     * Get the user that owns the notification log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Guest',
        ]);
    }

    /**
     * Scope a query to only include pending notifications.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope a query to only include failed notifications.
     */
    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    /**
     * Scope a query to only include notifications needing retry.
     */
    public function scopeNeedsRetry($query, int $maxRetries = 3)
    {
        return $query->where('status', self::STATUS_RETRYING)
            ->where('retry_count', '<', $maxRetries);
    }

    /**
     * Mark notification as sent.
     */
    public function markAsSent(): void
    {
        $now = now();
        $this->update([
            'status' => self::STATUS_SENT,
            'sent_at' => $now,
            'delivered_at' => $now, // Provider tidak support delivery status, anggap sent = delivered
        ]);
    }

    /**
     * Mark notification as delivered.
     */
    public function markAsDelivered(): void
    {
        $this->update([
            'status' => self::STATUS_DELIVERED,
            'delivered_at' => now(),
        ]);
    }

    /**
     * Mark notification as failed.
     */
    public function markAsFailed(string $errorCode): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'error_code' => $errorCode,
        ]);
    }

    /**
     * Increment retry count.
     */
    public function incrementRetry(): void
    {
        $this->increment('retry_count');
        if ($this->retry_count >= 3) {
            $this->update(['status' => self::STATUS_FAILED]);
        }
    }

    /**
     * Get delivery statistics.
     */
    public static function getStatistics(): array
    {
        return [
            'total_today' => self::whereDate('created_at', today())->count(),
            'pending' => self::where('status', self::STATUS_PENDING)->count(),
            'sent' => self::where('status', self::STATUS_SENT)->count(),
            'delivered' => self::where('status', self::STATUS_DELIVERED)->count(),
            'failed' => self::where('status', self::STATUS_FAILED)->count(),
            'success_rate' => self::calculateSuccessRate(),
        ];
    }

    /**
     * Calculate success rate.
     */
    private static function calculateSuccessRate(): float
    {
        $total = self::whereDate('created_at', today())->count();
        if ($total === 0) {
            return 100.0;
        }

        $successful = self::whereDate('created_at', today())
            ->whereIn('status', [self::STATUS_SENT, self::STATUS_DELIVERED])
            ->count();

        return round(($successful / $total) * 100, 2);
    }
}
