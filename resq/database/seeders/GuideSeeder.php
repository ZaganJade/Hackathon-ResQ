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
                'content' => '<p>Panduan lengkap evakuasi saat terjadi gempa bumi untuk menjaga keselamatan diri dan keluarga.</p>',
                'steps' => [
                    ['title' => 'Saat Gempa Terjadi', 'description' => 'Drop, Cover, and Hold On. Jatuhkan diri ke lantai, berlindung di bawah meja/kekuat, dan tunggu hingga gempa berhenti.'],
                    ['title' => 'Evaluasi Kerusakan', 'description' => 'Setelah gempa, periksa diri sendiri dan orang lain untuk cedera. Jangan masuk ke dalam bangunan yang rusak.'],
                    ['title' => 'Siapkan Aftershock', 'description' => 'Bersiap untuk gempa susulan. Simpan persediaan darurat dan tetap waspada.'],
                    ['title' => 'Ikuti Instruksi Evakuasi', 'description' => 'Ikuti petunjuk petugas SAR dan evakuasi ke tempat aman yang ditentukan.'],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Panduan Menghadapi Banjir',
                'category' => 'flood',
                'content' => '<p>Banjir adalah bencana yang sering terjadi di Indonesia. Ketahui cara menghadapinya.</p>',
                'steps' => [
                    ['title' => 'Pantau Peringatan Dini', 'description' => 'Ikuti informasi dari BMKG dan BPBD. Siapkan tas darurat jika diperlukan evakuasi.'],
                    ['title' => 'Pindahkan Barang Berharga', 'description' => 'Angkat perabot dan barang elektronik ke tempat tinggi. Matikan listrik dan gas.'],
                    ['title' => 'Evakuasi ke Tempat Tinggi', 'description' => 'Jika air naik cepat, segera ke lantai atas atau titik evakuasi. Jangan berjalan melalui air yang mengalir.'],
                    ['title' => 'Hindari Air Banjir', 'description' => 'Air banjir bisa terkontaminasi dan berbahaya. Jangan minum atau bermain di air banjir.'],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Protokol Erupsi Gunung Berapi',
                'category' => 'volcano',
                'content' => '<p>Ketahui apa yang harus dilakukan saat gunung berapi erupsi.</p>',
                'steps' => [
                    ['title' => 'Kenali Radius Bahaya', 'description' => 'Perhatikan status gunung api dan radius bahaya yang ditetapkan oleh PVMBG.'],
                    ['title' => 'Siapkan Masker', 'description' => 'Gunakan masker N95 atau kain basah saat ada abu vulkanik. Hindari area dengan konsentrasi abu tinggi.'],
                    ['title' => 'Lindungi Mata dan Kulit', 'description' => 'Gunakan kacamata pelindung dan tutupi kulit. Abu vulkanik bisa mengiritasi.'],
                    ['title' => 'Bersihkan Atap', 'description' => 'Keluarkan abu dari atap untuk mencegah keruntuhan. Hati-hati saat membersihkan.'],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Pertolongan Pertama Dasar',
                'category' => 'general',
                'content' => '<p>Keterampilan pertolongan pertama bisa menyelamatkan nyawa saat bencana.</p>',
                'steps' => [
                    ['title' => 'Pemeriksaan ABC', 'description' => 'Airway (jalan napas), Breathing (pernapasan), Circulation (sirkulasi darah). Pastikan ketiganya lancar.'],
                    ['title' => 'Kontrol Pendarahan', 'description' => 'Tekan luka yang berdarah dengan kain bersih. Angkat bagian tubuh yang terluka di atas jantung jika memungkinkan.'],
                    ['title' => 'Patah Tulang', 'description' => 'Imobilisasi bagian yang patah dengan splint. Jangan mencoba membetulkan sendiri.'],
                    ['title' => 'Shock', 'description' => 'Tanda shock: kulit pucat, dingin, dan berkeringat. Berikan kenyamanan dan hangat. Cari bantuan medis segera.'],
                ],
                'status' => 'published',
            ],
            [
                'title' => 'Persiapan Tas Darurat (Emergency Kit)',
                'category' => 'general',
                'content' => '<p>Tas darurat yang lengkap bisa menyelamatkan hidup Anda saat bencana.</p>',
                'steps' => [
                    ['title' => 'Air dan Makanan', 'description' => 'Minimal 3 liter air per orang per hari dan makanan tahan lama untuk 3 hari (kaleng, kering, energi bar).'],
                    ['title' => 'Obat-obatan', 'description' => 'Kotak P3K pribadi, obat rutin, dan resep dokter. Termasuk masker dan hand sanitizer.'],
                    ['title' => 'Dokumen Penting', 'description' => 'KTP, KK, SIM, sertifikat tanah, polis asuransi, dan uang tunai dalam jumlah kecil dalam wadah kedap air.'],
                    ['title' => 'Peralatan', 'description' => 'Senter, radio portabel, baterai cadangan, power bank, pisau multi-fungsi, dan selimut darurat.'],
                ],
                'status' => 'published',
            ],
        ];

        foreach ($guides as $guide) {
            Guide::create($guide);
        }
    }
}
