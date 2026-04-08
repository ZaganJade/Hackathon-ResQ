<?php

namespace Database\Factories;

use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationPreferenceFactory extends Factory
{
    protected $model = NotificationPreference::class;

    public function definition(): array
    {
        return [
            'user_id'         => User::factory(),
            'whatsapp_number' => '+628' . $this->faker->numerify('#########'),
            'disaster_types'  => $this->faker->randomElements(
                ['earthquake', 'flood', 'volcano', 'tsunami', 'landslide', 'fire', 'hurricane'],
                $this->faker->numberBetween(1, 4)
            ),
            'is_active' => true,
        ];
    }
}
