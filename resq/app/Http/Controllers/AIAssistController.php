<?php

namespace App\Http\Controllers;

use App\Services\AIAssistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AIAssistController extends Controller
{
    private AIAssistService $aiService;

    public function __construct(AIAssistService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Display the chat interface.
     */
    public function index()
    {
        return view('ai-assist.chat');
    }

    /**
     * Handle chat message and return AI response.
     */
    public function chat(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|max:2000',
            'conversation_id' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Pesan tidak valid. Maksimal 2000 karakter.',
            ], 422);
        }

        $user = auth()->user();
        $message = strip_tags($request->input('message'));
        $conversationId = $request->input('conversation_id');

        $startTime = microtime(true);

        $result = $this->aiService->chat($message, $user->id, $conversationId);

        $endTime = microtime(true);

        return response()->json([
            'success' => $result['success'],
            'reply' => $result['reply'],
            'conversation_id' => $result['conversation_id'],
            'response_time' => $result['response_time'] ?? round($endTime - $startTime, 3),
        ]);
    }

    /**
     * Get chat history for the authenticated user.
     */
    public function history(Request $request): JsonResponse
    {
        $user = auth()->user();
        $conversations = \App\Models\Chatlog::forUser($user->id)
            ->uniqueConversations()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'conversations' => $conversations,
        ]);
    }

    /**
     * Get messages for a specific conversation.
     */
    public function conversation(string $conversationId): JsonResponse
    {
        $user = auth()->user();

        // Verify user owns this conversation
        $hasAccess = \App\Models\Chatlog::forUser($user->id)
            ->conversation($conversationId)
            ->exists();

        if (!$hasAccess) {
            return response()->json([
                'success' => false,
                'error' => 'Percakapan tidak ditemukan.',
            ], 404);
        }

        $messages = \App\Models\Chatlog::forUser($user->id)
            ->conversation($conversationId)
            ->orderBy('created_at', 'asc')
            ->get(['role', 'message', 'created_at', 'metadata']);

        return response()->json([
            'success' => true,
            'conversation_id' => $conversationId,
            'messages' => $messages,
        ]);
    }

    /**
     * Start a new conversation.
     */
    public function newConversation(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'conversation_id' => 'conv_' . \Illuminate\Support\Str::random(16),
        ]);
    }
}
