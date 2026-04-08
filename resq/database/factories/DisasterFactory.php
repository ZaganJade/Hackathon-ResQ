<?php

namespace Database\Factories;

use App\Models\Disaster;
use Illuminate\Database\Eloquent\Factories\Factory;

class DisasterFactory extends Factory
{
    protected $model = Disaster::class;

    public function definition(): array
    {
        $types = ['earthquake', 'flood', 'volcano', 'tsunami', 'landslide', 'fire', 'hurricane'];
        $type = $this->faker->randomElement($types);

        return [
            'type'        => $type,
            'location'    => $this->faker->city() . ', Indonesia',
            'latitude'    => $this->faker->latitude(-10, 6),   // Indonesia latitude range
            'longitude'   => $this->faker->longitude(95, 141), // Indonesia longitude range
            'severity'    => $this->faker->randomElement(['low', 'medium', 'high', 'critical']),
            'status'      => $this->faker->randomElement(['active', 'resolved', 'monitoring']),
            'description' => $this->faker->sentence(10),
            'source'      => 'seeder',
            'source_id'   => null,
            'raw_data'    => $type === 'earthquake' ? ['magnitude' => round($this->faker->randomFloat(1, 4.0, 8.0), 1)] : null,
        ];
    }
}
