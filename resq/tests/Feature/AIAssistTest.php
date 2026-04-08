<?php

namespace Tests\Feature;

use App\Models\Chatlog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AIAssistTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    public function test_authenticated_user_can_access_chat_interface(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('ai-assist.index'));

        $response->assertStatus(200)
            ->assertViewIs('ai-assist.chat')
            ->assertSee('AI Assist ResQ')
            ->assertSee('Selamat datang');
    }

    public function test_guest_cannot_access_chat_interface(): void
    {
        $response = $this->get(route('ai-assist.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_send_chat_message(): void
    {
        // Mock Fireworks API response
        Http::fake([
            'https://api.fireworks.ai/inference/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Ini adalah jawaban untuk pertanyaan Anda tentang bencana.',
                            'role' => 'assistant',
                        ],
                        'finish_reason' => 'stop',
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->user)
            ->postJson(route('ai-assist.chat'), [
                'message' => 'Bagaimana cara menghadapi gempa bumi?',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'reply',
                'conversation_id',
                'response_time',
            ])
            ->assertJson([
                'success' => true,
            ]);

        // Verify chat logs were created
        $this->assertDatabaseHas('chatlogs', [
            'user_id' => $this->user->id,
            'role' => 'user',
            'message' => 'Bagaimana cara menghadapi gempa bumi?',
        ]);

        $this->assertDatabaseHas('chatlogs', [
            'user_id' => $this->user->id,
            'role' => 'assistant',
        ]);
    }

    public function test_chat_validates_empty_message(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('ai-assist.chat'), [
                'message' => '',
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_chat_validates_message_max_length(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('ai-assist.chat'), [
                'message' => str_repeat('a', 2001),
            ]);

        $response->assertStatus(422);
    }

    public function test_chat_sanitizes_html_in_message(): void
    {
        Http::fake([
            '*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Jawaban yang aman',
                            'role' => 'assistant',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $this->actingAs($this->user)
            ->postJson(route('ai-assist.chat'), [
                'message' => '<script>alert("xss")</script> Pertanyaan saya',
            ]);

        $this->assertDatabaseMissing('chatlogs', [
            'message' => '<script>alert("xss")</script> Pertanyaan saya',
        ]);
    }

    public function test_conversation_context_is_maintained(): void
    {
        Http::fake([
            '*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Jawaban AI',
                            'role' => 'assistant',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $conversationId = 'conv_test_123';

        // First message
        $response1 = $this->actingAs($this->user)
            ->postJson(route('ai-assist.chat'), [
                'message' => 'Pertanyaan pertama',
                'conversation_id' => $conversationId,
            ]);

        $response1->assertStatus(200);

        // Second message in same conversation
        $response2 = $this->actingAs($this->user)
            ->postJson(route('ai-assist.chat'), [
                'message' => 'Pertanyaan lanjutan',
                'conversation_id' => $conversationId,
            ]);

        $response2->assertStatus(200)
            ->assertJson([
                'conversation_id' => $conversationId,
            ]);

        // Verify both messages are in database
        $this->assertDatabaseCount('chatlogs', 4); // 2 user + 2 assistant
    }

    public function test_user_can_get_chat_history(): void
    {
        // Create some chat history
        Chatlog::create([
            'user_id' => $this->user->id,
            'conversation_id' => 'conv_1',
            'role' => 'user',
            'message' => 'Hello',
            'metadata' => [],
        ]);

        Chatlog::create([
            'user_id' => $this->user->id,
            'conversation_id' => 'conv_1',
            'role' => 'assistant',
            'message' => 'Hi there',
            'metadata' => [],
        ]);

        Chatlog::create([
            'user_id' => $this->user->id,
            'conversation_id' => 'conv_2',
            'role' => 'user',
            'message' => 'Another conversation',
            'metadata' => [],
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('ai-assist.history'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'conversations' => [
                    'data',
                    'current_page',
                    'total',
                ],
            ])
            ->assertJson([
                'success' => true,
            ]);
    }

    public function test_user_can_get_conversation_messages(): void
    {
        $conversationId = 'conv_specific';

        Chatlog::create([
            'user_id' => $this->user->id,
            'conversation_id' => $conversationId,
            'role' => 'user',
            'message' => 'Pertanyaan 1',
            'metadata' => [],
        ]);

        Chatlog::create([
            'user_id' => $this->user->id,
            'conversation_id' => $conversationId,
            'role' => 'assistant',
            'message' => 'Jawaban 1',
            'metadata' => [],
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('ai-assist.conversation', ['conversationId' => $conversationId]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'conversation_id',
                'messages' => [],
            ])
            ->assertJsonCount(2, 'messages')
            ->assertJson([
                'conversation_id' => $conversationId,
            ]);
    }

    public function test_user_cannot_access_other_users_conversation(): void
    {
        $otherUser = User::factory()->create();
        $conversationId = 'conv_other_user';

        Chatlog::create([
            'user_id' => $otherUser->id,
            'conversation_id' => $conversationId,
            'role' => 'user',
            'message' => 'Private message',
            'metadata' => [],
        ]);

        $response = $this->actingAs($this->user)
            ->getJson(route('ai-assist.conversation', ['conversationId' => $conversationId]));

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_user_can_start_new_conversation(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('ai-assist.new-conversation'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'conversation_id',
            ])
            ->assertJson([
                'success' => true,
            ]);

        $this->assertStringStartsWith('conv_', $response->json('conversation_id'));
    }

    public function test_chat_handles_api_failure(): void
    {
        Http::fake([
            '*' => Http::response([
                'error' => [
                    'message' => 'Internal Server Error',
                ],
            ], 500),
        ]);

        $response = $this->actingAs($this->user)
            ->postJson(route('ai-assist.chat'), [
                'message' => 'Test message',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => false,
            ])
            ->assertJsonPath('reply', function ($reply) {
                return str_contains($reply, 'Maaf');
            });
    }

    public function test_chat_handles_api_timeout(): void
    {
        Http::fake([
            '*' => Http::timeout(),
        ]);

        $response = $this->actingAs($this->user)
            ->postJson(route('ai-assist.chat'), [
                'message' => 'Test message',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_response_time_is_included_in_response(): void
    {
        Http::fake([
            '*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => 'Jawaban cepat',
                            'role' => 'assistant',
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->user)
            ->postJson(route('ai-assist.chat'), [
                'message' => 'Test',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'response_time',
            ]);

        $this->assertNotNull($response->json('response_time'));
        $this->assertIsNumeric($response->json('response_time'));
    }
}
