<?php

namespace Database\Factories;

use App\Models\Chatlog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Chatlog>
 */
class ChatlogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Chatlog>
     */
    protected $model = Chatlog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'conversation_id' => 'conv_' . fake()->unique()->regexify('[a-zA-Z0-9]{16}'),
            'role' => fake()->randomElement(['user', 'assistant']),
            'message' => fake()->sentence(),
            'metadata' => [],
        ];
    }

    /**
     * Set the role to user.
     */
    public function user(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'user',
        ]);
    }

    /**
     * Set the role to assistant.
     */
    public function assistant(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'assistant',
        ]);
    }
}
