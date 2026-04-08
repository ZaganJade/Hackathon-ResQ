<?php

namespace Database\Seeders;

use App\Models\Chatlog;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ChatlogSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        $conversations = [
            [
                'topic' => 'earthquake-safety',
                'messages' => [
                    ['role' => 'user', 'message' => 'Apa yang harus saya lakukan saat gempa bumi?'],
                    ['role' => 'assistant', 'message' => 'Saat gempa bumi, ikuti prosedur "Drop, Cover, and Hold On": 1. Jatuhkan diri ke lantai, 2. Berlindung di bawah meja atau furnitur yang kuat, 3. Pegang erat dan tunggu sampai guncangan berhenti. Hindari jendela dan benda berat yang bisa jatuh.'],
                    ['role' => 'user', 'message' => 'Berapa lama biasanya gempa berlangsung?'],
                    ['role' => 'assistant', 'message' => 'Gempa utama biasanya berlangsung 20-60 detik. Namun, aftershock atau gempa susulan bisa terjadi dalam hitungan jam, hari, atau bahkan minggu. Tetap siaga dan bersiaplah untuk gempa susulan.'],
                ]
            ],
            [
                'topic' => 'flood-preparation',
                'messages' => [
                    ['role' => 'user', 'message' => 'Bagaimana cara mempersiapkan diri menghadapi banjir?'],
                    ['role' => 'assistant', 'message' => 'Persiapan menghadapi banjir meliputi: 1. Perbaiki atap dan sistem drainase, 2. Siapkan tas darurat dengan pakaian dan obat-obatan, 3. Elevasi barang berharga, 4. Ketahui rute evakuasi, 5. Pantau prakiraan cuaca dari BMKG.'],
                    ['role' => 'user', 'message' => 'Apa saja yang sebaiknya ada dalam tas darurat?'],
                    ['role' => 'assistant', 'message' => 'Tas darurat harus berisi: 1. Air minum (3 liter per orang per hari), 2. Makanan tahan lama (3 hari), 3. Obat-obatan dan P3K, 4. Dokumen penting dalam wadah kedap air, 5. Senter dan baterai, 6. Radio portabel, 7. Power bank, 8. Pakaian ganti.'],
                ]
            ],
            [
                'topic' => 'first-aid',
                'messages' => [
                    ['role' => 'user', 'message' => 'Saya ingin belajar pertolongan pertama dasar'],
                    ['role' => 'assistant', 'message' => 'Pertolongan pertama dasar dimulai dengan ABC: 1. Airway (jalan napas) - pastikan tidak tersumbat, 2. Breathing (pernapasan) - periksa apakah korban bernapas, 3. Circulation (sirkulasi) - periksa nadi. Jika ada masalah, segera hubungi ambulans.'],
                    ['role' => 'user', 'message' => 'Bagaimana menangani pendarahan?'],
                    ['role' => 'assistant', 'message' => 'Untuk menangani pendarahan: 1. Tekan luka dengan kain bersih selama 5-10 menit, 2. Jangan melepas kain jika darah masih keluar, tumpuk kain lagi, 3. Angkat bagian yang luka di atas jantung, 4. Jika pendarahan tidak berhenti, segera ke rumah sakit.'],
                ]
            ],
            [
                'topic' => 'volcano-safety',
                'messages' => [
                    ['role' => 'user', 'message' => 'Saya tinggal dekat gunung api. Apa yang perlu saya ketahui?'],
                    ['role' => 'assistant', 'message' => 'Hal penting jika tinggal dekat gunung api: 1. Ketahui radius bahaya, 2. Ikuti status gunung api dari PVMBG, 3. Siapkan masker N95 untuk abu vulkanik, 4. Tahu rute evakuasi, 5. Miliki tas darurat yang siap evakuasi.'],
                ]
            ],
        ];

        foreach ($users as $userIndex => $user) {
            foreach ($conversations as $convIndex => $conversation) {
                $conversationId = Str::uuid();
                
                foreach ($conversation['messages'] as $msgIndex => $messageData) {
                    Chatlog::create([
                        'user_id' => $user->id,
                        'conversation_id' => $conversationId,
                        'role' => $messageData['role'],
                        'message' => $messageData['message'],
                        'metadata' => json_encode([
                            'topic' => $conversation['topic'],
                            'sequence' => $msgIndex + 1,
                        ]),
                        'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
                        'updated_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 23)),
                    ]);
                }
            }
        }
    }
}
