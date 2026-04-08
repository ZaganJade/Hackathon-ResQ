<?php

namespace App\Services;

use App\Models\Chatlog;
use App\Services\ExternalApi\FireworksService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * AI Assist Service
 * Provides AI chat functionality using Fireworks AI
 *
 * Updated to use FireworksService from Task 13 for:
 * - Circuit breaker protection (13.6)
 * - Response caching (13.7)
 * - Fallback handling (13.8)
 * - API monitoring (13.9)
 */
class AIAssistService
{
    private FireworksService $fireworks;
    private string $systemPrompt;
    private int $maxContextMessages;

    public function __construct(?FireworksService $fireworks = null)
    {
        $this->fireworks = $fireworks ?? app(FireworksService::class);
        $this->systemPrompt = config('resq.ai_system_prompt', 'Anda adalah asisten AI ResQ yang membantu masyarakat Indonesia dengan informasi mitigasi bencana.');
        $this->maxContextMessages = 10; // Keep last 10 messages for context
    }

    /**
     * Send a chat message to the AI and get a response.
     *
     * @param string $message The user's message
     * @param int $userId The user ID for storing chat history
     * @param string|null $conversationId Optional conversation ID for context
     * @return array Response with reply, conversation_id, and metadata
     */
    public function chat(string $message, int $userId, ?string $conversationId = null): array
    {
        $conversationId = $conversationId ?? $this->generateConversationId();
        $startTime = microtime(true);

        try {
            // Get conversation history for context
            $contextMessages = $this->getConversationContext($userId, $conversationId);

            // Build the messages array
            $messages = $this->buildMessages($message, $contextMessages);

            // Call Fireworks AI API through resilient service
            $response = $this->fireworks->chat($messages);
            $aiContent = $response['content'] ?? 'Maaf, saya tidak dapat memproses permintaan Anda saat ini.';

            $endTime = microtime(true);
            $responseTime = round($endTime - $startTime, 3);

            // Save user message
            Chatlog::create([
                'user_id' => $userId,
                'conversation_id' => $conversationId,
                'role' => 'user',
                'message' => $message,
                'metadata' => ['timestamp' => now()->toIso8601String()],
            ]);

            // Save AI response
            Chatlog::create([
                'user_id' => $userId,
                'conversation_id' => $conversationId,
                'role' => 'assistant',
                'message' => $aiContent,
                'metadata' => [
                    'response_time' => $responseTime,
                    'model' => $response['model'] ?? 'unknown',
                    'finish_reason' => $response['finish_reason'] ?? null,
                    'usage' => $response['usage'] ?? [],
                    'timestamp' => now()->toIso8601String(),
                ],
            ]);

            return [
                'reply' => $aiContent,
                'conversation_id' => $conversationId,
                'response_time' => $responseTime,
                'success' => true,
            ];

        } catch (\Throwable $e) {
            Log::error('AI Assist Service error', [
                'message' => $e->getMessage(),
                'user_id' => $userId,
                'conversation_id' => $conversationId,
            ]);

            return [
                'reply' => 'Maaf, sistem AI sedang mengalami gangguan. Silakan coba lagi dalam beberapa saat.',
                'conversation_id' => $conversationId,
                'response_time' => null,
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Quick chat without saving history
     *
     * @param string $message User message
     * @return string AI response
     */
    public function chatQuick(string $message): string
    {
        $messages = [
            ['role' => 'system', 'content' => $this->systemPrompt],
            ['role' => 'user', 'content' => $message],
        ];

        try {
            $response = $this->fireworks->chat($messages);
            return $response['content'] ?? 'Maaf, saya tidak dapat menjawab saat ini.';
        } catch (\Throwable $e) {
            Log::warning('Quick chat failed', ['error' => $e->getMessage()]);
            return 'Maaf, layanan AI tidak tersedia. Silakan coba lagi nanti.';
        }
    }

    /**
     * Build the messages array for the API call.
     *
     * @param string $userMessage The current user message
     * @param array $contextMessages Previous messages for context
     * @return array
     */
    private function buildMessages(string $userMessage, array $contextMessages): array
    {
        $messages = [];

        // Add context messages (limited to prevent token overflow)
        $recentMessages = array_slice($contextMessages, -$this->maxContextMessages);
        foreach ($recentMessages as $msg) {
            $messages[] = [
                'role' => $msg['role'],
                'content' => $msg['message'],
            ];
        }

        // Add current user message
        $messages[] = [
            'role' => 'user',
            'content' => $userMessage,
        ];

        return $messages;
    }

    /**
     * Get conversation context from chat history.
     *
     * @param int $userId
     * @param string $conversationId
     * @return array
     */
    private function getConversationContext(int $userId, string $conversationId): array
    {
        return Chatlog::forUser($userId)
            ->conversation($conversationId)
            ->whereIn('role', ['user', 'assistant'])
            ->orderBy('created_at', 'asc')
            ->get(['role', 'message'])
            ->toArray();
    }

    /**
     * Generate a unique conversation ID.
     *
     * @return string
     */
    private function generateConversationId(): string
    {
        return 'conv_' . Str::random(16);
    }

    /**
     * Check if the service is healthy.
     *
     * @return bool
     */
    public function isHealthy(): bool
    {
        return $this->fireworks->validateConnection();
    }

    /**
     * Get AI service status
     *
     * @return array
     */
    public function getStatus(): array
    {
        return $this->fireworks->getStatus();
    }
}
