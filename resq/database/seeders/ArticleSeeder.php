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
                'excerpt' => 'Pelajari teknik DROP, COVER, dan HOLD ON untuk menyelamatkan diri saat gempa bumi terjadi di dekat Anda.',
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
                <h3>2. Siapkan Tas Darurat</h3>
                <p>Sediakan pakaian, obat-obatan, dokumen penting, dan makanan tahan lama.</p>
                <h3>3. Pantau Informasi Cuaca</h3>
                <p>Ikuti perkembangan cuaca dari BMKG dan siaga untuk evakuasi jika diperlukan.</p>
                <h3>4. Tingkatkan Sistem Drainase</h3>
                <p>Bersihkan saluran air dan pastikan sistem drainase berfungsi dengan baik.</p>',
                'excerpt' => 'Siapkan diri Anda dan keluarga menghadapi musim hujan dengan langkah-langkah pencegahan banjir yang efektif.',
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
                'excerpt' => 'Ketahui cara membaca status gunung api dan radius bahaya untuk keselamatan keluarga Anda.',
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
                'excerpt' => 'Simulasi evakuasi tsunami yang efektif bisa menyelamatkan nyawa. Pelajari langkah-langkahnya di sini.',
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
                'excerpt' => 'Kenalkan platform ResQ yang revolusioner untuk informasi dan mitigasi bencana di Indonesia.',
                'category' => 'general',
                'status' => 'published',
                'published_at' => now(),
                'view_count' => 3200,
            ],
            [
                'title' => 'Tanah Longsor: Tanda-Tanda Awal dan Pencegahan',
                'content' => '<p>Tanah longsor sering terjadi di daerah berbukit atau bergunung, terutama saat musim hujan.</p>
                <h3>Tanda-Tanda Awal Tanah Longsor:</h3>
                <ul>
                <li>Retakan di permukaan tanah</li>
                <li>Pohon atau tiang yang miring</li>
                <li>Munculnya aliran air baru di tengah lereng</li>
                <li>Suara gemuruh dari dalam tanah</li>
                <li>Hilangnya sebagian jalan atau bangunan</li>
                </ul>
                <h3>Pencegahan Tanah Longsor:</h3>
                <ol>
                <li>Jangan menebang pohon di lereng yang curam</li>
                <li>Perkuat tebing dengan pembangunan teracing</li>
                <li>Perlancar sistem drainase di area yang rentan</li>
                <li>Hindari aktivitas tambang ilegal</li>
                </ol>',
                'excerpt' => 'Ketahui tanda-tanda awal dan cara mencegah tanah longsor di area berbukit.',
                'category' => 'landslide',
                'status' => 'published',
                'published_at' => now()->subDays(12),
                'view_count' => 945,
            ],
            [
                'title' => 'Panduan Lengkap Pertolongan Pertama di Tempat Kejadian',
                'content' => '<p>Pertolongan pertama yang tepat dapat menyelamatkan nyawa seseorang. Setiap orang harus mengetahui dasar-dasarnya.</p>
                <h3>Prinsip ABC (Airway, Breathing, Circulation):</h3>
                <p>Periksa dalam urutan berikut: jalan napas terbuka, pernapasan ada, denyut jantung teraba.</p>
                <h3>Resusitasi Jantung Paru (RJP):</h3>
                <ol>
                <li>Posisikan korban terlentang di permukaan datar dan keras</li>
                <li>Lakukan kompresi dada dengan kecepatan 100-120 kali per menit</li>
                <li>Berikan napas buatan dengan perbandingan 30:2</li>
                <li>Lanjutkan sampai korban sadar atau bantuan medis tiba</li>
                </ol>',
                'excerpt' => 'Kuasai teknik pertolongan pertama dasar yang bisa menyelamatkan nyawa dalam situasi darurat.',
                'category' => 'general',
                'status' => 'published',
                'published_at' => now()->subDays(15),
                'view_count' => 2340,
            ],
            [
                'title' => 'Kebakaran Hutan: Dampak dan Cara Menghadapi',
                'content' => '<p>Kebakaran hutan mengancam ekosistem dan kesehatan masyarakat. Ketahui cara menghadapinya.</p>
                <h3>Dampak Kebakaran Hutan:</h3>
                <ul>
                <li>Polusi udara yang berbahaya bagi kesehatan</li>
                <li>Kehilangan habitat satwa liar</li>
                <li>Tanah menjadi kering dan gersang</li>
                <li>Emisi gas rumah kaca yang meningkat</li>
                </ul>
                <h3>Cara Menghadapi Kebakaran Hutan:</h3>
                <ol>
                <li>Gunakan masker N95 saat kualitas udara buruk</li>
                <li>Tetap di dalam ruangan dengan AC jika memungkinkan</li>
                <li>Hindari olahraga outdoor saat asap tebal</li>
                <li>Tingkatkan asupan vitamin C dan air</li>
                </ol>',
                'excerpt' => 'Pahami dampak kebakaran hutan dan cara melindungi kesehatan Anda saat polusi udara tinggi.',
                'category' => 'fire',
                'status' => 'published',
                'published_at' => now()->subDays(8),
                'view_count' => 1678,
            ],
            [
                'title' => 'Kekeringan: Konservasi Air dan Adaptasi',
                'content' => '<p>Indonesia menghadapi tantangan kekeringan di beberapa wilayah. Konservasi air menjadi sangat penting.</p>
                <h3>Tips Konservasi Air:</h3>
                <ul>
                <li>Matikan keran saat menyikat gigi atau mencuci piring</li>
                <li>Gunakan toilet flush yang efisien</li>
                <li>Manfaatkan air hujan untuk kebutuhan rumah tangga</li>
                <li>Tanam tanaman yang tahan kekeringan</li>
                <li>Perbaiki kebocoran pipa dengan segera</li>
                </ul>
                <h3>Adaptasi pada Kekeringan:</h3>
                <ol>
                <li>Siapkan cadangan air untuk 1-2 minggu</li>
                <li>Bergabunglah dengan inisiatif penghijauan komunitas</li>
                <li>Dukung kebijakan pengelolaan air yang berkelanjutan</li>
                </ol>',
                'excerpt' => 'Lakukan konservasi air dan adaptasi dengan kekeringan melalui langkah-langkah sederhana namun efektif.',
                'category' => 'drought',
                'status' => 'published',
                'published_at' => now()->subDays(20),
                'view_count' => 756,
            ],
        ];

        foreach ($articles as $article) {
            Article::create(array_merge($article, [
                'author_id' => $admin->id,
                'slug' => \Illuminate\Support\Str::slug($article['title']),
            ]));
        }
    }
}
