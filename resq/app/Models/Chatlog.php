<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chatlog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'conversation_id',
        'role',
        'message',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user that owns the chatlog.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include messages for a specific conversation.
     */
    public function scopeConversation($query, string $conversationId)
    {
        return $query->where('conversation_id', $conversationId);
    }

    /**
     * Scope a query to only include messages for a specific user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get unique conversations for a user.
     */
    public function scopeUniqueConversations($query)
    {
        return $query->select('conversation_id')
            ->selectRaw('MIN(created_at) as started_at')
            ->selectRaw('MAX(created_at) as last_message_at')
            ->selectRaw('COUNT(*) as message_count')
            ->groupBy('conversation_id')
            ->orderByDesc('last_message_at');
    }

    /**
     * Scope a query to only include non-deleted messages.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateBetween($query, $from, $to)
    {
        return $query->when($from, function ($q) use ($from) {
            return $q->whereDate('created_at', '>=', $from);
        })->when($to, function ($q) use ($to) {
            return $q->whereDate('created_at', '<=', $to);
        });
    }

    /**
     * Scope to search in message content.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where('message', 'like', '%' . $search . '%');
    }

    /**
     * Get conversation statistics for a user.
     */
    public static function getUserStats(int $userId): array
    {
        $totalConversations = static::forUser($userId)
            ->selectRaw('COUNT(DISTINCT conversation_id) as count')
            ->first()
            ?->count ?? 0;

        $totalMessages = static::forUser($userId)
            ->whereNull('deleted_at')
            ->count();

        $avgResponseTime = static::forUser($userId)
            ->where('role', 'assistant')
            ->whereRaw("metadata->>'response_time' IS NOT NULL")
            ->selectRaw("AVG(CAST(metadata->>'response_time' AS DECIMAL(10,3))) as avg_time")
            ->first()
            ?->avg_time ?? 0;

        $firstConversation = static::forUser($userId)
            ->min('created_at');

        return [
            'total_conversations' => (int) $totalConversations,
            'total_messages' => (int) $totalMessages,
            'avg_response_time' => round((float) $avgResponseTime, 3),
            'first_conversation_date' => $firstConversation,
        ];
    }

    /**
     * Get conversation preview data.
     */
    public static function getConversationPreview(string $conversationId, int $userId): ?object
    {
        $messages = static::forUser($userId)
            ->conversation($conversationId)
            ->active()
            ->orderBy('created_at', 'asc')
            ->get();

        if ($messages->isEmpty()) {
            return null;
        }

        $firstMessage = $messages->firstWhere('role', 'user');
        $lastMessage = $messages->last();

        return (object) [
            'conversation_id' => $conversationId,
            'title' => $firstMessage ? self::generateTitle($firstMessage->message) : 'Percakapan Tanpa Judul',
            'started_at' => $messages->first()->created_at,
            'last_message_at' => $lastMessage->created_at,
            'message_count' => $messages->count(),
            'preview' => $lastMessage->message,
        ];
    }

    /**
     * Generate a title from the first message.
     */
    private static function generateTitle(string $message): string
    {
        $cleanMessage = strip_tags($message);
        $title = substr($cleanMessage, 0, 50);

        if (strlen($cleanMessage) > 50) {
            $title .= '...';
        }

        return $title ?: 'Percakapan Baru';
    }

    /**
     * Soft delete an entire conversation.
     */
    public static function softDeleteConversation(string $conversationId, int $userId): int
    {
        return static::forUser($userId)
            ->conversation($conversationId)
            ->update(['deleted_at' => now()]);
    }

    /**
     * Restore a soft-deleted conversation.
     */
    public static function restoreConversation(string $conversationId, int $userId): int
    {
        return static::forUser($userId)
            ->conversation($conversationId)
            ->withTrashed()
            ->update(['deleted_at' => null]);
    }
}
