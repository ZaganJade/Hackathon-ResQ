<?php

namespace Database\Seeders;

use App\Models\Guide;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuideSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guides = [
            [
                'title' => 'Tata Cara Evakuasi Saat Gempa Bumi',
                'category' => 'earthquake',
                'content' => '<p>Panduan lengkap evakuasi saat terjadi gempa bumi untuk menjaga keselamatan diri dan keluarga.</p>
                <h3>Fase Sebelum Gempa:</h3>
                <ul>
                <li>Ketahui rute evakuasi terdekat dari rumah dan kantor</li>
                <li>Siapkan tas darurat dengan dokumen penting</li>
                <li>Ajarkan anggota keluarga tentang prosedur keselamatan</li>
                </ul>',
                'slug' => 'tata-cara-evakuasi-gempa',
                'steps' => json_encode([
                    ['title' => 'Saat Gempa Terjadi', 'description' => 'Drop, Cover, and Hold On. Jatuhkan diri ke lantai, berlindung di bawah meja/kekuat, dan tunggu hingga gempa berhenti.'],
                    ['title' => 'Evaluasi Kerusakan', 'description' => 'Setelah gempa, periksa diri sendiri dan orang lain untuk cedera. Jangan masuk ke dalam bangunan yang rusak.'],
                    ['title' => 'Siapkan Aftershock', 'description' => 'Bersiap untuk gempa susulan. Simpan persediaan darurat dan tetap waspada.'],
                    ['title' => 'Ikuti Instruksi Evakuasi', 'description' => 'Ikuti petunjuk petugas SAR dan evakuasi ke tempat aman yang ditentukan.'],
                    ['title' => 'Cari Tempat Aman', 'description' => 'Hindari pantai, jembatan, jalan overpass, dan bangunan tinggi yang rusak.'],
                ]),
                'status' => 'published',
            ],
            [
                'title' => 'Panduan Menghadapi Banjir',
                'category' => 'flood',
                'content' => '<p>Banjir adalah bencana yang sering terjadi di Indonesia. Ketahui cara menghadapinya dengan prosedur yang tepat.</p>',
                'slug' => 'panduan-menghadapi-banjir',
                'steps' => json_encode([
                    ['title' => 'Pantau Peringatan Dini', 'description' => 'Ikuti informasi dari BMKG dan BPBD. Siapkan tas darurat jika diperlukan evakuasi.'],
                    ['title' => 'Pindahkan Barang Berharga', 'description' => 'Angkat perabot dan barang elektronik ke tempat tinggi. Matikan listrik dan gas sebelum air masuk.'],
                    ['title' => 'Evakuasi ke Tempat Tinggi', 'description' => 'Jika air naik cepat, segera ke lantai atas atau titik evakuasi. Jangan berjalan melalui air yang mengalir.'],
                    ['title' => 'Hindari Air Banjir', 'description' => 'Air banjir bisa terkontaminasi dan berbahaya. Jangan minum atau bermain di air banjir.'],
                    ['title' => 'Tunggu Instruksi Resmi', 'description' => 'Tetap di tempat aman sampai air surut dan ada izin untuk kembali dari pihak berwenang.'],
                ]),
                'status' => 'published',
            ],
            [
                'title' => 'Protokol Erupsi Gunung Berapi',
                'category' => 'volcano',
                'content' => '<p>Ketahui apa yang harus dilakukan saat gunung berapi erupsi. Panduan ini penting untuk keselamatan jiwa.</p>',
                'slug' => 'protokol-erupsi-gunung-berapi',
                'steps' => json_encode([
                    ['title' => 'Kenali Radius Bahaya', 'description' => 'Perhatikan status gunung api dan radius bahaya yang ditetapkan oleh PVMBG.'],
                    ['title' => 'Siapkan Masker', 'description' => 'Gunakan masker N95 atau kain basah saat ada abu vulkanik. Hindari area dengan konsentrasi abu tinggi.'],
                    ['title' => 'Lindungi Mata dan Kulit', 'description' => 'Gunakan kacamata pelindung dan tutupi kulit. Abu vulkanik bisa mengiritasi.'],
                    ['title' => 'Bersihkan Atap', 'description' => 'Keluarkan abu dari atap untuk mencegah keruntuhan. Hati-hati saat membersihkan.'],
                    ['title' => 'Ikuti Evakuasi', 'description' => 'Jika menerima perintah evakuasi, segera tinggalkan area. Bawa dokumen penting dan keluarga.'],
                ]),
                'status' => 'published',
            ],
            [
                'title' => 'Pertolongan Pertama Dasar',
                'category' => 'general',
                'content' => '<p>Keterampilan pertolongan pertama bisa menyelamatkan nyawa saat bencana. Panduan ini untuk pengetahuan dasar.</p>',
                'slug' => 'pertolongan-pertama-dasar',
                'steps' => json_encode([
                    ['title' => 'Pemeriksaan ABC', 'description' => 'Airway (jalan napas), Breathing (pernapasan), Circulation (sirkulasi darah). Pastikan ketiganya lancar.'],
                    ['title' => 'Kontrol Pendarahan', 'description' => 'Tekan luka yang berdarah dengan kain bersih. Angkat bagian tubuh yang terluka di atas jantung jika memungkinkan.'],
                    ['title' => 'Patah Tulang', 'description' => 'Imobilisasi bagian yang patah dengan splint atau penahan. Jangan mencoba membetulkan sendiri.'],
                    ['title' => 'Shock', 'description' => 'Tanda shock: kulit pucat, dingin, dan berkeringat. Berikan kenyamanan dan hangat. Cari bantuan medis segera.'],
                ]),
                'status' => 'published',
            ],
            [
                'title' => 'Persiapan Tas Darurat (Emergency Kit)',
                'category' => 'general',
                'content' => '<p>Tas darurat yang lengkap bisa menyelamatkan hidup Anda saat bencana. Siapkan sekarang juga.</p>',
                'slug' => 'persiapan-tas-darurat',
                'steps' => json_encode([
                    ['title' => 'Air dan Makanan', 'description' => 'Minimal 3 liter air per orang per hari dan makanan tahan lama untuk 3 hari (kaleng, kering, energi bar).'],
                    ['title' => 'Obat-obatan', 'description' => 'Kotak P3K pribadi, obat rutin, dan resep dokter. Termasuk masker dan hand sanitizer.'],
                    ['title' => 'Dokumen Penting', 'description' => 'KTP, KK, SIM, sertifikat tanah, polis asuransi, dan uang tunai dalam jumlah kecil dalam wadah kedap air.'],
                    ['title' => 'Peralatan', 'description' => 'Senter, radio portabel, baterai cadangan, power bank, pisau multi-fungsi, dan selimut darurat.'],
                    ['title' => 'Pakaian dan Perlengkapan', 'description' => 'Pakaian ganti, sepatu yang nyaman, jaket, dan perlengkapan hygiene dasar.'],
                ]),
                'status' => 'published',
            ],
            [
                'title' => 'Evakuasi Tsunami: Panduan Cepat',
                'category' => 'tsunami',
                'content' => '<p>Tsunami bisa tiba dengan cepat. Panduan cepat ini membantu Anda bertindak dalam beberapa detik.</p>',
                'slug' => 'evakuasi-tsunami-panduan-cepat',
                'steps' => json_encode([
                    ['title' => 'Rasakan Gempa Kuat', 'description' => 'Jika merasakan gempa yang sangat kuat atau durasi panjang, tsunami mungkin akan datang.'],
                    ['title' => 'Jangan Menunggu', 'description' => 'JANGAN MENUNGGU PERINGATAN RESMI. Segera lari ke tempat tinggi atau jauh dari pantai.'],
                    ['title' => 'Lari ke Tempat Tinggi', 'description' => 'Lari ke ketinggian minimal 30 meter atau 3 km dari garis pantai. Gunakan kendaraan jika perlu.'],
                    ['title' => 'Hindari Lembah', 'description' => 'Hindari lembah sungai dan estuari. Tsunami bisa naik ke atas sungai.'],
                    ['title' => 'Tetap Tinggi', 'description' => 'Tetap di tempat aman hingga ada konfirmasi resmi dari pihak berwenang bahwa aman turun.'],
                ]),
                'status' => 'published',
            ],
            [
                'title' => 'Mitigasi Tanah Longsor',
                'category' => 'landslide',
                'content' => '<p>Tanah longsor bisa dicegah dengan tindakan mitigasi yang tepat. Panduan ini untuk komunitas di area rawan longsor.</p>',
                'slug' => 'mitigasi-tanah-longsor',
                'steps' => json_encode([
                    ['title' => 'Identifikasi Area Rawan', 'description' => 'Ketahui apakah rumah Anda di area rawan longsor. Hubungi BPBD untuk pemetaan risiko.'],
                    ['title' => 'Perkuat Lereng', 'description' => 'Lakukan penghijauan dan bangun teracing untuk memperkuat lereng. Hindari menebang pohon di lereng curam.'],
                    ['title' => 'Kelola Air', 'description' => 'Bangun sistem drainase yang baik. Hindari genangan air yang dapat melunakkan tanah.'],
                    ['title' => 'Asuransi dan Donasi', 'description' => 'Pertimbangkan asuransi bencana. Dukung program penghijauan komunitas.'],
                    ['title' => 'Siapkan Evakuasi', 'description' => 'Ketahui rute evakuasi dan area aman. Siapkan tas darurat.'],
                ]),
                'status' => 'published',
            ],
            [
                'title' => 'Menghadapi Kebakaran Hutan',
                'category' => 'fire',
                'content' => '<p>Kebakaran hutan bisa membawa dampak serius. Panduan ini membantu Anda melindungi diri saat terjadi kebakaran hutan.</p>',
                'slug' => 'menghadapi-kebakaran-hutan',
                'steps' => json_encode([
                    ['title' => 'Monitor Status Kebakaran', 'description' => 'Pantau berita dan status kebakaran dari BPBD. Siapkan tas evakuasi jika api semakin dekat.'],
                    ['title' => 'Gunakan Masker N95', 'description' => 'Gunakan masker N95 berkualitas saat kualitas udara buruk. Pastikan segel masker dengan baik.'],
                    ['title' => 'Lindungi Rumah', 'description' => 'Tutup jendela dan pintu. Gunakan air conditioning jika ada. Basahi atap dengan air.'],
                    ['title' => 'Hindari Outdoor', 'description' => 'Hindari olahraga dan aktivitas di luar ruangan. Tetap di dalam rumah atau tempat dengan udara bersih.'],
                    ['title' => 'Segera Evakuasi', 'description' => 'Jika pemerintah memerintahkan evakuasi, segera tinggalkan area dengan membawa dokumen dan barang penting.'],
                ]),
                'status' => 'published',
            ],
            [
                'title' => 'Bertahan Saat Kekeringan',
                'category' => 'drought',
                'content' => '<p>Kekeringan dapat berlangsung lama. Panduan ini membantu Anda dan keluarga bertahan saat kekeringan.</p>',
                'slug' => 'bertahan-saat-kekeringan',
                'steps' => json_encode([
                    ['title' => 'Hemat Air', 'description' => 'Matikan keran saat tidak dipakai. Gunakan air secukupnya untuk kebutuhan pokok. Manfaatkan air hujan dan air bekas untuk kebutuhan sekunder.'],
                    ['title' => 'Cari Sumber Air Alternatif', 'description' => 'Gali sumur dalam atau sumur bor jika memungkinkan. Kumpulkan air hujan menggunakan tangki. Hubungi BPBD untuk bantuan air.'],
                    ['title' => 'Perlindungan Kesehatan', 'description' => 'Minum air yang telah dimasak atau disaring. Hindari makanan yang memerlukan banyak air dalam pengolahannya. Perhatikan kebersihan.'],
                    ['title' => 'Adaptasi Pertanian', 'description' => 'Gunakan benih yang tahan kekeringan. Tanam pada musim hujan dan kelola irigasi dengan bijak.'],
                    ['title' => 'Dukungan Komunitas', 'description' => 'Bergabung dengan program penghijauan dan reboisasi. Dukung kebijakan pengelolaan air yang berkelanjutan.'],
                ]),
                'status' => 'published',
            ],
        ];

        foreach ($guides as $guide) {
            Guide::create($guide);
        }
    }
}
