<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('is_admin', true)->first();

        $articles = [
            [
                'title' => 'Cara Menyelamatkan Diri Saat Gempa Bumi',
                'content' => '<p>Gempa bumi adalah bencana alam yang sering terjadi di Indonesia. Berikut adalah langkah-langkah untuk menyelamatkan diri saat gempa:</p>
                <h3>1. Drop, Cover, and Hold On</h3>
                <p>Immediately drop to the ground, take cover under a sturdy table or desk, and hold on until the shaking stops.</p>
                <h3>2. Jauhi Jendela dan Benda Berat</h3>
                <p>Stay away from windows, glass, and heavy furniture that could fall.</p>
                <h3>3. Jika di Luar Ruangan</h3>
                <p>Move to an open area away from buildings, trees, and power lines.</p>
                <h3>4. Setelah Gempa Berhenti</h3>
                <p>Check for injuries, be prepared for aftershocks, and follow evacuation procedures if necessary.</p>',
                'category' => 'earthquake',
                'status' => 'published',
                'published_at' => now()->subDays(10),
                'view_count' => 1250,
            ],
            [
                'title' => 'Persiapan Menghadapi Musim Hujan dan Banjir',
                'content' => '<p>Musim hujan telah tiba. Berikut persiapan yang perlu dilakukan:</p>
                <h3>1. Periksa Atap dan Talang</h3>
                <p>Pastikan tidak ada kebocoran atau kerusakan.</p>
                <h3>3. Siapkan Tas Darurat</h3>
                <p>Sediakan pakaian, obat-obatan, dokumen penting, dan makanan tahan lama.</p>
                <h3>4. Pantau Informasi Cuaca</h3>
                <p>Ikuti perkembangan cuaca dari BMKG dan siaga untuk evakuasi jika diperlukan.</p>',
                'category' => 'flood',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'view_count' => 890,
            ],
            [
                'title' => 'Pahami Status Gunung Berapi di Sekitar Anda',
                'content' => '<p>Indonesia memiliki banyak gunung berapi aktif. Penting untuk memahami status dan bahaya yang ditimbulkan.</p>
                <h3>Level Status Gunung Api:</h3>
                <ul>
                <li><strong>Normal (Level I)</strong>: Aktivitas vulkanik normal</li>
                <li><strong>Waspada (Level II)</strong>: Peningkatan aktivitas, radius berbahaya 3 km</li>
                <li><strong>Siaga (Level III)</strong>: Aktivitas meningkat signifikan, radius 5 km</li>
                <li><strong>Awas (Level IV)</strong>: Erupsi iminen, radius 7-10 km</li>
                </ul>',
                'category' => 'volcano',
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'view_count' => 2100,
            ],
            [
                'title' => 'Simulasi Evakuasi Tsunami: Panduan Lengkap',
                'content' => '<p>Tsunami bisa terjadi kapan saja tanpa peringatan. Simulasi evakuasi sangat penting untuk mempersiapkan diri.</p>
                <h3>Langkah Evakuasi Tsunami:</h3>
                <ol>
                <li>Jika merasakan gempa kuat, segera lari ke tempat tinggi</li>
                <li>Jangan menunggu peringatan resmi</li>
                <li>Lari minimal 3 km dari garis pantai atau ke ketinggian 30 meter</li>
                <li>Hindari lembah sungai dan estuari</li>
                <li>Tetap di tempat aman sampai ada konfirmasi resmi</li>
                </ol>',
                'category' => 'tsunami',
                'status' => 'published',
                'published_at' => now()->subDays(7),
                'view_count' => 1560,
            ],
            [
                'title' => 'ResQ: Platform Baru untuk Informasi Bencana',
                'content' => '<p>ResQ adalah platform mitigasi bencana yang dirancang untuk masyarakat Indonesia. Dengan fitur AI Assist, peta bencana real-time, dan notifikasi WhatsApp, ResQ membantu Anda tetap aman dan terinformasi.</p>
                <h3>Fitur Utama ResQ:</h3>
                <ul>
                <li><strong>AI Assist</strong>: Tanyakan apa saja tentang bencana dan mitigasi</li>
                <li><strong>Peta Bencana</strong>: Pantau lokasi bencana secara real-time</li>
                <li><strong>Notifikasi WhatsApp</strong>: Dapatkan peringatan darurat</li>
                <li><strong>Artikel & Panduan</strong>: Pelajari cara menghadapi bencana</li>
                </ul>
                <p>Daftar sekarang untuk mendapatkan akses penuh ke semua fitur ResQ.</p>',
                'category' => 'general',
                'status' => 'published',
                'published_at' => now(),
                'view_count' => 3200,
            ],
        ];

        foreach ($articles as $article) {
            Article::create(array_merge($article, [
                'author_id' => $admin->id,
            ]));
        }
    }
}
