<?php

namespace App\Http\Controllers;

use App\Models\Chatlog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ChatHistoryController extends Controller
{
    /**
     * Display chat history list for the authenticated user.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        // Get filter parameters
        $search = $request->input('search');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Build query for conversations
        $conversationQuery = Chatlog::forUser($user->id)
            ->active()
            ->select('conversation_id')
            ->selectRaw('MIN(created_at) as started_at')
            ->selectRaw('MAX(created_at) as last_message_at')
            ->selectRaw('COUNT(*) as message_count')
            ->groupBy('conversation_id')
            ->orderByDesc('last_message_at');

        // Apply date filters
        if ($fromDate) {
            $conversationQuery->whereDate('created_at', '>=', $fromDate);
        }
        if ($toDate) {
            $conversationQuery->whereDate('created_at', '<=', $toDate);
        }

        // Get paginated conversations
        $conversations = $conversationQuery->paginate(10);

        // Enrich conversation data with preview
        $enrichedConversations = $conversations->map(function ($conv) use ($user, $search) {
            $preview = Chatlog::getConversationPreview($conv->conversation_id, $user->id);

            // Filter by search term if provided
            if ($search && $preview) {
                $hasMatch = Chatlog::forUser($user->id)
                    ->conversation($conv->conversation_id)
                    ->active()
                    ->search($search)
                    ->exists();

                if (!$hasMatch) {
                    return null;
                }
            }

            return $preview;
        })->filter();

        // Get user statistics
        $stats = Chatlog::getUserStats($user->id);

        return view('chat-history.index', [
            'conversations' => $enrichedConversations,
            'pagination' => $conversations,
            'stats' => $stats,
            'filters' => [
                'search' => $search,
                'from_date' => $fromDate,
                'to_date' => $toDate,
            ],
        ]);
    }

    /**
     * Show detailed view of a specific conversation.
     */
    public function show(string $conversationId): View
    {
        $user = auth()->user();

        // Verify user owns this conversation
        $hasAccess = Chatlog::forUser($user->id)
            ->conversation($conversationId)
            ->active()
            ->exists();

        if (!$hasAccess) {
            abort(404, 'Percakapan tidak ditemukan.');
        }

        // Get conversation messages
        $messages = Chatlog::forUser($user->id)
            ->conversation($conversationId)
            ->active()
            ->orderBy('created_at', 'asc')
            ->get();

        // Get conversation preview for header
        $preview = Chatlog::getConversationPreview($conversationId, $user->id);

        // Get conversation metadata
        $metadata = [
            'total_messages' => $messages->count(),
            'user_messages' => $messages->where('role', 'user')->count(),
            'ai_messages' => $messages->where('role', 'assistant')->count(),
            'started_at' => $messages->first()?->created_at,
            'ended_at' => $messages->last()?->created_at,
            'avg_response_time' => $messages
                ->where('role', 'assistant')
                ->whereNotNull('metadata.response_time')
                ->avg('metadata.response_time'),
        ];

        return view('chat-history.show', [
            'conversationId' => $conversationId,
            'title' => $preview?->title ?? 'Percakapan',
            'messages' => $messages,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Search conversations by content.
     */
    public function search(Request $request): JsonResponse
    {
        $user = auth()->user();
        $search = $request->input('q');

        if (empty($search) || strlen($search) < 2) {
            return response()->json([
                'success' => false,
                'error' => 'Kata kunci pencarian minimal 2 karakter.',
            ], 422);
        }

        // Find conversations containing search term
        $matchingConversations = Chatlog::forUser($user->id)
            ->active()
            ->search($search)
            ->select('conversation_id')
            ->distinct()
            ->pluck('conversation_id');

        // Get preview for each matching conversation
        $results = $matchingConversations->map(function ($conversationId) use ($user) {
            return Chatlog::getConversationPreview($conversationId, $user->id);
        })->filter()->values();

        return response()->json([
            'success' => true,
            'query' => $search,
            'results' => $results,
            'count' => $results->count(),
        ]);
    }

    /**
     * Soft delete an entire conversation.
     */
    public function destroy(string $conversationId): JsonResponse
    {
        $user = auth()->user();

        // Verify user owns this conversation
        $hasAccess = Chatlog::forUser($user->id)
            ->conversation($conversationId)
            ->active()
            ->exists();

        if (!$hasAccess) {
            return response()->json([
                'success' => false,
                'error' => 'Percakapan tidak ditemukan.',
            ], 404);
        }

        // Soft delete all messages in the conversation
        $deletedCount = Chatlog::softDeleteConversation($conversationId, $user->id);

        return response()->json([
            'success' => true,
            'message' => 'Percakapan berhasil dihapus.',
            'deleted_messages' => $deletedCount,
        ]);
    }

    /**
     * Restore a soft-deleted conversation.
     */
    public function restore(string $conversationId): JsonResponse
    {
        $user = auth()->user();

        // Restore conversation
        $restoredCount = Chatlog::restoreConversation($conversationId, $user->id);

        if ($restoredCount === 0) {
            return response()->json([
                'success' => false,
                'error' => 'Percakapan tidak ditemukan atau tidak dapat dipulihkan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Percakapan berhasil dipulihkan.',
            'restored_messages' => $restoredCount,
        ]);
    }

    /**
     * Get user chat statistics.
     */
    public function stats(): JsonResponse
    {
        $user = auth()->user();
        $stats = Chatlog::getUserStats($user->id);

        // Get recent activity (conversations per day for last 7 days)
        $recentActivity = Chatlog::forUser($user->id)
            ->active()
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('COUNT(DISTINCT conversation_id) as conversation_count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'recent_activity' => $recentActivity,
        ]);
    }

    /**
     * Export conversation as text or JSON.
     */
    public function export(string $conversationId, Request $request): JsonResponse|\Illuminate\Http\Response
    {
        $user = auth()->user();
        $format = $request->input('format', 'json');

        // Verify user owns this conversation
        $messages = Chatlog::forUser($user->id)
            ->conversation($conversationId)
            ->active()
            ->orderBy('created_at', 'asc')
            ->get();

        if ($messages->isEmpty()) {
            return response()->json([
                'success' => false,
                'error' => 'Percakapan tidak ditemukan.',
            ], 404);
        }

        $preview = Chatlog::getConversationPreview($conversationId, $user->id);

        if ($format === 'text') {
            $content = "ResQ Chat Export\n";
            $content .= "==================\n\n";
            $content .= "Judul: {$preview->title}\n";
            $content .= "Tanggal: {$preview->started_at->format('d M Y H:i')}\n";
            $content .= "Pesan: {$messages->count()}\n\n";
            $content .= "------------------\n\n";

            foreach ($messages as $message) {
                $role = $message->role === 'user' ? 'Anda' : 'AI ResQ';
                $timestamp = $message->created_at->format('d M Y H:i');
                $content .= "[{$timestamp}] {$role}:\n{$message->message}\n\n";
            }

            return response($content, 200, [
                'Content-Type' => 'text/plain',
                'Content-Disposition' => "attachment; filename=\"chat-{$conversationId}.txt\"",
            ]);
        }

        // Default JSON format
        return response()->json([
            'success' => true,
            'conversation' => [
                'id' => $conversationId,
                'title' => $preview->title,
                'started_at' => $preview->started_at,
                'last_message_at' => $preview->last_message_at,
                'message_count' => $messages->count(),
                'messages' => $messages->map(function ($msg) {
                    return [
                        'role' => $msg->role,
                        'message' => $msg->message,
                        'created_at' => $msg->created_at,
                        'metadata' => $msg->metadata,
                    ];
                }),
            ],
        ]);
    }
}
