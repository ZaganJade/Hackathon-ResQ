<?php

namespace App\Services;

use App\Models\Chatlog;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AIAssistService
{
    private Client $httpClient;
    private string $apiKey;
    private string $apiEndpoint;
    private string $model;
    private string $systemPrompt;
    private int $timeout;

    public function __construct()
    {
        $this->httpClient = new Client([
            'timeout' => 30,
            'connect_timeout' => 10,
        ]);
        $this->apiKey = config('services.fireworks.api_key');
        $this->apiEndpoint = config('services.fireworks.endpoint', 'https://api.fireworks.ai/inference/v1/chat/completions');
        $this->model = config('services.fireworks.model', 'accounts/fireworks/models/llama-v3p1-70b-instruct');
        $this->systemPrompt = config('resq.ai_system_prompt', 'Anda adalah asisten AI ResQ yang membantu masyarakat Indonesia dengan informasi mitigasi bencana.');
        $this->timeout = config('resq.ai_timeout', 3);
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

            // Call Fireworks AI API
            $response = $this->callFireworksAPI($messages);

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
                'message' => $response,
                'metadata' => [
                    'response_time' => $responseTime,
                    'model' => $this->model,
                    'timestamp' => now()->toIso8601String(),
                ],
            ]);

            return [
                'reply' => $response,
                'conversation_id' => $conversationId,
                'response_time' => $responseTime,
                'success' => true,
            ];

        } catch (RequestException $e) {
            Log::error('Fireworks AI API error', [
                'message' => $e->getMessage(),
                'user_id' => $userId,
                'conversation_id' => $conversationId,
            ]);

            return [
                'reply' => 'Maaf, terjadi kesalahan saat memproses pesan Anda. Silakan coba lagi nanti.',
                'conversation_id' => $conversationId,
                'response_time' => null,
                'success' => false,
                'error' => $e->getMessage(),
            ];
        } catch (\Exception $e) {
            Log::error('AI Assist Service error', [
                'message' => $e->getMessage(),
                'user_id' => $userId,
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
     * Call the Fireworks AI API.
     *
     * @param array $messages The formatted messages
     * @return string The AI response
     * @throws RequestException
     */
    private function callFireworksAPI(array $messages): string
    {
        $response = $this->httpClient->post($this->apiEndpoint, [
            'headers' => [
                'Authorization' => "Bearer {$this->apiKey}",
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => $this->model,
                'messages' => $messages,
                'max_tokens' => 1024,
                'temperature' => 0.7,
                'top_p' => 0.9,
            ],
            'timeout' => $this->timeout,
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return $data['choices'][0]['message']['content'] ?? 'Maaf, saya tidak dapat memproses permintaan Anda saat ini.';
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
        $messages = [
            [
                'role' => 'system',
                'content' => $this->systemPrompt,
            ],
        ];

        // Add context messages (last 5 messages to stay within token limits)
        $recentMessages = array_slice($contextMessages, -5);
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
        return !empty($this->apiKey) && !empty($this->apiEndpoint);
    }
}
