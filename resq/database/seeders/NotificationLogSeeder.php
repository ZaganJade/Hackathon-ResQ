<?php

namespace Database\Seeders;

use App\Models\NotificationLog;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationLogSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('is_admin', false)->get();
        $disasterTypes = ['Gempa Bumi', 'Banjir', 'Erupsi Gunung', 'Tsunami', 'Tanah Longsor'];
        $statuses = ['delivered', 'sent', 'failed', 'pending'];
        $errorCodes = ['200', '400', '401', '403', '500', '503'];

        foreach ($users as $user) {
            // Create 5-15 notification logs per user
            for ($i = 0; $i < rand(5, 15); $i++) {
                $status = $statuses[array_rand($statuses)];
                $isDelivered = $status === 'delivered';
                $isFailed = $status === 'failed';
                
                $createdAt = now()->subDays(rand(0, 60))->subHours(rand(0, 23));
                
                NotificationLog::create([
                    'user_id' => $user->id,
                    'phone_number' => $this->generatePhoneNumber(),
                    'message' => $this->generateNotificationMessage($disasterTypes),
                    'status' => $status,
                    'error_code' => $isFailed ? $errorCodes[array_rand($errorCodes)] : null,
                    'retry_count' => $isFailed ? rand(0, 3) : 0,
                    'sent_at' => $status !== 'pending' ? $createdAt->copy()->addMinutes(rand(1, 5)) : null,
                    'delivered_at' => $isDelivered ? $createdAt->copy()->addMinutes(rand(6, 15)) : null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt->copy()->addMinutes(rand(1, 30)),
                ]);
            }
        }
    }

    /**
     * Generate random Indonesian phone number
     */
    private function generatePhoneNumber(): string
    {
        $carriers = ['62812', '62813', '62814', '62815', '62816', '62817', '62818', '62819', '62821', '62822'];
        $carrier = $carriers[array_rand($carriers)];
        $number = rand(10000000, 99999999);
        return '+' . $carrier . $number;
    }

    /**
     * Generate random notification message
     */
    private function generateNotificationMessage(array $disasterTypes): string
    {
        $disaster = $disasterTypes[array_rand($disasterTypes)];
        $locations = ['Jakarta', 'Bandung', 'Yogyakarta', 'Surabaya', 'Medan', 'Bali', 'Padang', 'Makassar', 'Palembang', 'Cianjur'];
        $location = $locations[array_rand($locations)];
        
        $templates = [
            "⚠️ PERINGATAN: {$disaster} terdeteksi di {$location}. Tingkat keparahan: TINGGI. Ikuti instruksi pemerintah setempat.",
            "🚨 ALERT: {$disaster} aktif di area {$location}. Bersiaplah untuk evakuasi. Info: www.resq.id",
            "⚠️ Perhatian: Gempa susulan terdeteksi di {$location}. Tetap waspada. Hubungi 112 untuk bantuan.",
            "📢 UPDATE BENCANA: {$disaster} di {$location}. Status: MONITORING. Pantau info terbaru di BMKG.",
        ];
        
        return $templates[array_rand($templates)];
    }
}
