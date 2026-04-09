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
    private LocationRiskService $locationRisk;
    private string $systemPrompt;
    private int $maxContextMessages;

    public function __construct(?FireworksService $fireworks = null, ?LocationRiskService $locationRisk = null)
    {
        $this->fireworks = $fireworks ?? app(FireworksService::class);
        $this->locationRisk = $locationRisk ?? app(LocationRiskService::class);
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
     * Chat with location-aware context for personalized disaster warnings
     *
     * @param string $message User message
     * @param int $userId User ID
     * @param float $lat User latitude
     * @param float $lng User longitude
     * @param string|null $conversationId Conversation ID
     * @return array Response with location-aware AI reply
     */
    public function chatWithLocation(string $message, int $userId, float $lat, float $lng, ?string $conversationId = null): array
    {
        $conversationId = $conversationId ?? $this->generateConversationId();
        $startTime = microtime(true);

        try {
            // Analyze zone status based on user's location
            $zoneAnalysis = $this->locationRisk->analyzeZoneStatus($lat, $lng);
            $riskTrend = $this->locationRisk->getRiskTrend($lat, $lng);

            // Get conversation context
            $contextMessages = $this->getConversationContext($userId, $conversationId);

            // Build enhanced system prompt with location context
            $enhancedSystemPrompt = $this->buildLocationAwarePrompt($zoneAnalysis, $riskTrend);

            // Build messages with location context
            $messages = $this->buildLocationAwareMessages($message, $contextMessages, $zoneAnalysis, $enhancedSystemPrompt);

            // Call AI API
            $response = $this->fireworks->chat($messages);
            $aiContent = $response['content'] ?? 'Maaf, saya tidak dapat memproses permintaan Anda saat ini.';

            $endTime = microtime(true);
            $responseTime = round($endTime - $startTime, 3);

            // Save chat logs with location metadata
            $this->saveChatWithLocation($userId, $conversationId, $message, $aiContent, $responseTime, $response, $lat, $lng, $zoneAnalysis);

            return [
                'reply' => $aiContent,
                'conversation_id' => $conversationId,
                'response_time' => $responseTime,
                'success' => true,
                'location_context' => [
                    'zone_status' => $zoneAnalysis['status'],
                    'zone_label' => $zoneAnalysis['status_label'],
                    'zone_color' => $zoneAnalysis['status_color'],
                    'warning_message' => $zoneAnalysis['warning_message'],
                    'nearby_disasters_count' => $zoneAnalysis['metrics']['total_nearby_disasters'],
                    'recommendations' => $zoneAnalysis['recommendations'],
                ],
            ];

        } catch (\Throwable $e) {
            Log::error('AI Assist with location error', [
                'message' => $e->getMessage(),
                'user_id' => $userId,
                'location' => ['lat' => $lat, 'lng' => $lng],
            ]);

            return [
                'reply' => 'Maaf, sistem AI sedang mengalami gangguan. Silakan coba lagi dalam beberapa saat.',
                'conversation_id' => $conversationId ?? $this->generateConversationId(),
                'response_time' => null,
                'success' => false,
                'error' => $e->getMessage(),
                'location_context' => null,
            ];
        }
    }

    /**
     * Build location-aware system prompt
     */
    private function buildLocationAwarePrompt(array $zoneAnalysis, array $riskTrend): string
    {
        $basePrompt = $this->systemPrompt;
        $status = $zoneAnalysis['status'];
        $statusLabel = $zoneAnalysis['status_label'];
        $warningMessage = $zoneAnalysis['warning_message'] ?? '';
        $recommendations = $zoneAnalysis['recommendations'] ?? [];

        $locationContext = "\n\n=== KONTEKS LOKASI USER ===\n";
        $locationContext .= "Status Zona Saat Ini: {$statusLabel}\n";
        $locationContext .= "Peringatan: {$warningMessage}\n";
        $locationContext .= "Total Bencana di Sekitar (50km): {$zoneAnalysis['metrics']['total_nearby_disasters']}\n";
        $locationContext .= "Cluster Maksimum (bencana berdekatan waktu): {$zoneAnalysis['metrics']['max_cluster_size']}\n";
        $locationContext .= "Trend Risiko: {$riskTrend['trend']}\n";

        if (!empty($zoneAnalysis['disasters_by_type'])) {
            $locationContext .= "Tipe Bencana di Area:\n";
            foreach ($zoneAnalysis['disasters_by_type'] as $type => $count) {
                $locationContext .= "- {$type}: {$count}\n";
            }
        }

        if (!empty($recommendations)) {
            $locationContext .= "\nRekomendasi untuk User:\n";
            foreach (array_slice($recommendations, 0, 3) as $rec) {
                $locationContext .= "- {$rec}\n";
            }
        }

        $locationContext .= "\n=== INSTRUKSI KHUSUS ===\n";

        // Add status-specific instructions
        switch ($status) {
            case LocationRiskService::STATUS_DANGER:
                $locationContext .= "USER BERADA DI ZONA BERBAHAYA. Prioritaskan informasi evakuasi dan keselamatan. ";
                $locationContext .= "Berikan peringatan tegas dan instruksi yang jelas. ";
                $locationContext .= "Jika user bertanya hal tidak penting, arahkan kembali ke keselamatan.\n";
                break;
            case LocationRiskService::STATUS_WARNING:
                $locationContext .= "USER DI ZONA WASPADA. Berikan informasi kesiapsiagaan dan pantauan. ";
                $locationContext .= "Bantu user memahami risiko dan langkah pencegahan.\n";
                break;
            case LocationRiskService::STATUS_SAFE:
                $locationContext .= "USER DI ZONA AMAN. Tetap berikan edukasi mitigasi bencana. ";
                $locationContext .= "Bantu user memahami potensi risiko dan kesiapsiagaan umum.\n";
                break;
        }

        // Add disaster type prioritization
        if (!empty($zoneAnalysis['disasters_by_type'])) {
            $topDisasterTypes = array_slice(array_keys($zoneAnalysis['disasters_by_type']), 0, 3);
            $locationContext .= "\n=== PRIORITAS MITIGASI ===\n";
            $locationContext .= "Berdasarkan data historis, bencana yang SERING TERJADI di area ini adalah: ";
            $locationContext .= implode(', ', $topDisasterTypes) . ".\n";
            $locationContext .= "ANDA WAJIB memprioritaskan mitigasi dan persiapan untuk bencana-bencana tersebut.\n";
            $locationContext .= "Setiap jawaban Anda harus mempertimbangkan risiko spesifik dari tipe bencana di atas.\n";

            // Add specific guidance based on top disaster types
            foreach ($topDisasterTypes as $type) {
                $mitigationFocus = match($type) {
                    'earthquake' => 'fokus pada evakuasi saat gempa, titik aman (bawah meja), dan periksa struktur bangunan',
                    'flood' => 'fokus pada aman dokumen, barang berharga di tempat tinggi, dan jalur evakuasi ke daratan tinggi',
                    'tsunami' => 'fokus pada rute evakuasi ke daratan tinggi dan tanda peringatan tsunami',
                    'volcanic_eruption' => 'fokus pada masker N95, perlindungan pernapasan, dan barang cadangan untuk isolasi',
                    'landslide' => 'fokus pada tanda pergerakan tanah dan hindari area lereng saat hujan deras',
                    'fire' => 'fokus pada jalur keluar darurat, pemadam api ringan, dan tidak menggunakan lift saat kebakaran',
                    default => "fokus pada persiapan mitigasi khusus {$type}",
                };
                $locationContext .= "- Untuk {$type}: {$mitigationFocus}\n";
            }
        }

        $locationContext .= "\nSelalu sebutkan status zona saat ini dalam respons Anda.";
        $locationContext .= " Berikan saran yang relevan dengan tipe bencana di area tersebut.";
        $locationContext .= " Gunakan bahasa yang tenang tapi jelas.";

        return $basePrompt . $locationContext;
    }

    /**
     * Build messages array with location context
     */
    private function buildLocationAwareMessages(string $userMessage, array $contextMessages, array $zoneAnalysis, string $systemPrompt): array
    {
        $messages = [];

        // Add system prompt with location context
        $messages[] = [
            'role' => 'system',
            'content' => $systemPrompt,
        ];

        // Add context messages
        $recentMessages = array_slice($contextMessages, -$this->maxContextMessages);
        foreach ($recentMessages as $msg) {
            $messages[] = [
                'role' => $msg['role'],
                'content' => $msg['message'],
            ];
        }

        // Add user message with location prefix
        $locationPrefix = $this->getLocationPrefix($zoneAnalysis['status']);
        $enhancedMessage = $locationPrefix . $userMessage;

        $messages[] = [
            'role' => 'user',
            'content' => $enhancedMessage,
        ];

        return $messages;
    }

    /**
     * Get prefix for user message based on zone status
     */
    private function getLocationPrefix(string $status): string
    {
        return match ($status) {
            LocationRiskService::STATUS_DANGER => "[LOKASI USER: ZONA BERBAHAYA] ",
            LocationRiskService::STATUS_WARNING => "[LOKASI USER: ZONA WASPADA] ",
            LocationRiskService::STATUS_SAFE => "[LOKASI USER: ZONA AMAN] ",
            default => "",
        };
    }

    /**
     * Save chat logs with location metadata
     */
    private function saveChatWithLocation(
        int $userId,
        string $conversationId,
        string $message,
        string $aiContent,
        float $responseTime,
        array $response,
        float $lat,
        float $lng,
        array $zoneAnalysis
    ): void {
        // Save user message
        Chatlog::create([
            'user_id' => $userId,
            'conversation_id' => $conversationId,
            'role' => 'user',
            'message' => $message,
            'metadata' => [
                'timestamp' => now()->toIso8601String(),
                'location' => ['lat' => $lat, 'lng' => $lng],
            ],
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
                'location' => ['lat' => $lat, 'lng' => $lng],
                'zone_status' => $zoneAnalysis['status'],
                'zone_analysis' => $zoneAnalysis['metrics'],
            ],
        ]);
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
