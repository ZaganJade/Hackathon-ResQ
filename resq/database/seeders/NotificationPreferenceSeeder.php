<?php

namespace Database\Seeders;

use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationPreferenceSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('is_admin', false)->get();

        foreach ($users as $index => $user) {
            // Vary the phone numbers for demonstration
            $baseNumber = 62 . (8 + ($index % 3)) . rand(1000000000, 9999999999);
            
            NotificationPreference::create([
                'user_id' => $user->id,
                'whatsapp_number' => '+' . $baseNumber,
                'disaster_types' => json_encode([
                    'earthquake',
                    'flood',
                    'volcano',
                    'tsunami',
                ]),
                'min_alert_level' => $this->getRandomAlertLevel(),
                'is_active' => $index % 10 !== 0 ? true : false, // 90% active
            ]);
        }
    }

    /**
     * Get random alert level
     */
    private function getRandomAlertLevel(): string
    {
        $levels = ['low', 'moderate', 'high', 'critical'];
        return $levels[array_rand($levels)];
    }
}
