<?php

namespace Tests\Unit\Services;

use App\Models\Chatlog;
use App\Models\User;
use App\Services\AIAssistService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AIAssistServiceTest extends TestCase
{
    use RefreshDatabase;

    private AIAssistService $aiService;
    private MockHandler $mockHandler;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Set up configuration
        Config::set('services.fireworks.api_key', 'test_api_key');
        Config::set('services.fireworks.endpoint', 'https://api.test.fireworks.ai/inference/v1/chat/completions');
        Config::set('services.fireworks.model', 'accounts/fireworks/models/test-model');
        Config::set('resq.ai_system_prompt', 'Test system prompt');
        Config::set('resq.ai_timeout', 30);

        // Create mock Guzzle client
        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $httpClient = new Client(['handler' => $handlerStack]);

        // Create service with mocked client
        $this->aiService = new AIAssistService();

        // Use reflection to inject mock client
        $reflection = new \ReflectionClass($this->aiService);
        $httpClientProperty = $reflection->getProperty('httpClient');
        $httpClientProperty->setAccessible(true);
        $httpClientProperty->setValue($this->aiService, $httpClient);
    }

    public function test_chat_returns_successful_response(): void
    {
        // Arrange
        $aiResponse = 'Ini adalah respons dari AI untuk bantuan bencana.';
        $responseBody = json_encode([
            'choices' => [
                [
                    'message' => [
                        'content' => $aiResponse,
                        'role' => 'assistant',
                    ],
                    'finish_reason' => 'stop',
                    'index' => 0,
                ],
            ],
            'usage' => [
                'prompt_tokens' => 50,
                'completion_tokens' => 20,
                'total_tokens' => 70,
            ],
        ]);

        $this->mockHandler->append(new Response(200, [], $responseBody));

        // Act
        $result = $this->aiService->chat('Bagaimana cara menghadapi banjir?', $this->user->id);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertEquals($aiResponse, $result['reply']);
        $this->assertNotNull($result['conversation_id']);
        $this->assertIsFloat($result['response_time']);

        // Check chat logs were created
        $chatlogs = Chatlog::where('user_id', $this->user->id)->get();
        $this->assertCount(2, $chatlogs);
        $this->assertEquals('user', $chatlogs[0]->role);
        $this->assertEquals('Bagaimana cara menghadapi banjir?', $chatlogs[0]->message);
        $this->assertEquals('assistant', $chatlogs[1]->role);
        $this->assertEquals($aiResponse, $chatlogs[1]->message);
    }

    public function test_chat_uses_provided_conversation_id(): void
    {
        // Arrange
        $conversationId = 'conv_test_12345';
        $responseBody = json_encode([
            'choices' => [
                [
                    'message' => [
                        'content' => 'Respons AI',
                        'role' => 'assistant',
                    ],
                ],
            ],
        ]);

        $this->mockHandler->append(new Response(200, [], $responseBody));

        // Act
        $result = $this->aiService->chat('Test message', $this->user->id, $conversationId);

        // Assert
        $this->assertEquals($conversationId, $result['conversation_id']);
    }

    public function test_chat_includes_conversation_context(): void
    {
        // Arrange
        $conversationId = 'conv_context_test';

        // Create previous messages in conversation
        Chatlog::create([
            'user_id' => $this->user->id,
            'conversation_id' => $conversationId,
            'role' => 'user',
            'message' => 'Pertanyaan sebelumnya',
            'metadata' => [],
        ]);

        Chatlog::create([
            'user_id' => $this->user->id,
            'conversation_id' => $conversationId,
            'role' => 'assistant',
            'message' => 'Jawaban sebelumnya',
            'metadata' => [],
        ]);

        $responseBody = json_encode([
            'choices' => [
                [
                    'message' => [
                        'content' => 'Respons dengan konteks',
                        'role' => 'assistant',
                    ],
                ],
            ],
        ]);

        $this->mockHandler->append(new Response(200, [], $responseBody));

        // Act
        $result = $this->aiService->chat('Pertanyaan lanjutan', $this->user->id, $conversationId);

        // Assert
        $this->assertTrue($result['success']);

        // Verify all messages are saved
        $chatlogs = Chatlog::where('conversation_id', $conversationId)->get();
        $this->assertCount(4, $chatlogs);
    }

    public function test_chat_handles_api_error_gracefully(): void
    {
        // Arrange
        $this->mockHandler->append(new Response(500, [], json_encode([
            'error' => ['message' => 'Internal Server Error']
        ])));

        // Act
        $result = $this->aiService->chat('Test message', $this->user->id);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Maaf', $result['reply']);
        $this->assertNotNull($result['conversation_id']);
    }

    public function test_chat_handles_network_timeout(): void
    {
        // Arrange
        $this->mockHandler->append(new \GuzzleHttp\Exception\ConnectException(
            'Connection timeout',
            new \GuzzleHttp\Psr7\Request('POST', 'test'),
            null,
            null
        ));

        // Act
        $result = $this->aiService->chat('Test message', $this->user->id);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Maaf', $result['reply']);
    }

    public function test_chat_sanitizes_malicious_input(): void
    {
        // Arrange
        $maliciousInput = '<script>alert("xss")</script>Bagaimana cara evakuasi?';
        $responseBody = json_encode([
            'choices' => [
                [
                    'message' => [
                        'content' => 'Jawaban yang aman',
                        'role' => 'assistant',
                    ],
                ],
            ],
        ]);

        $this->mockHandler->append(new Response(200, [], $responseBody));

        // Act
        $result = $this->aiService->chat($maliciousInput, $this->user->id);

        // Assert
        $this->assertTrue($result['success']);

        // Verify the malicious script is not stored as-is
        $userMessage = Chatlog::where('role', 'user')->first();
        $this->assertStringNotContainsString('<script>', $userMessage->message);
    }

    public function test_chat_response_time_is_tracked(): void
    {
        // Arrange
        $responseBody = json_encode([
            'choices' => [
                [
                    'message' => [
                        'content' => 'Respons cepat',
                        'role' => 'assistant',
                    ],
                ],
            ],
        ]);

        $this->mockHandler->append(new Response(200, [], $responseBody));

        // Act
        $startTime = microtime(true);
        $result = $this->aiService->chat('Test', $this->user->id);
        $endTime = microtime(true);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertNotNull($result['response_time']);
        $this->assertGreaterThan(0, $result['response_time']);
        $this->assertLessThan($endTime - $startTime + 1, $result['response_time']);

        // Verify response time is stored in metadata
        $assistantMessage = Chatlog::where('role', 'assistant')->first();
        $this->assertArrayHasKey('response_time', $assistantMessage->metadata);
    }

    public function test_is_healthy_returns_true_with_valid_config(): void
    {
        // Act & Assert
        $this->assertTrue($this->aiService->isHealthy());
    }

    public function test_chat_limits_context_to_recent_messages(): void
    {
        // Arrange
        $conversationId = 'conv_many_messages';

        // Create 10 previous messages
        for ($i = 0; $i < 10; $i++) {
            Chatlog::create([
                'user_id' => $this->user->id,
                'conversation_id' => $conversationId,
                'role' => $i % 2 === 0 ? 'user' : 'assistant',
                'message' => "Message {$i}",
                'metadata' => [],
            ]);
        }

        $responseBody = json_encode([
            'choices' => [
                [
                    'message' => [
                        'content' => 'Respons terbatas konteks',
                        'role' => 'assistant',
                    ],
                ],
            ],
        ]);

        $this->mockHandler->append(new Response(200, [], $responseBody));

        // Act
        $result = $this->aiService->chat('Pertanyaan terbaru', $this->user->id, $conversationId);

        // Assert
        $this->assertTrue($result['success']);

        // Should now have 12 messages (10 old + user question + AI response)
        $chatlogs = Chatlog::where('conversation_id', $conversationId)->get();
        $this->assertCount(12, $chatlogs);
    }

    public function test_chat_handles_empty_api_response(): void
    {
        // Arrange
        $responseBody = json_encode([
            'choices' => [],
        ]);

        $this->mockHandler->append(new Response(200, [], $responseBody));

        // Act
        $result = $this->aiService->chat('Test message', $this->user->id);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertStringContainsString('Maaf', $result['reply']);
    }

    public function test_chat_handles_missing_choices_in_response(): void
    {
        // Arrange
        $responseBody = json_encode([
            'usage' => [
                'total_tokens' => 50,
            ],
        ]);

        $this->mockHandler->append(new Response(200, [], $responseBody));

        // Act
        $result = $this->aiService->chat('Test message', $this->user->id);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertStringContainsString('Maaf', $result['reply']);
    }
}
