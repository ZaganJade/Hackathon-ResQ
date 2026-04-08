<?php

namespace Database\Seeders;

use App\Models\Disaster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DisasterSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $disasters = [
            [
                'type' => 'earthquake',
                'location' => 'Cianjur, Jawa Barat',
                'latitude' => -6.78,
                'longitude' => 107.13,
                'severity' => 'high',
                'status' => 'resolved',
                'description' => 'Gempa bumi dengan magnitudo 5.6 mengguncang Cianjur pada November 2022. Ribuan rumah rusak dan ratusan korban jiwa.',
                'source' => 'manual',
                'raw_data' => ['magnitude' => 5.6, 'depth' => 10],
            ],
            [
                'type' => 'earthquake',
                'location' => 'Banten',
                'latitude' => -6.20,
                'longitude' => 106.00,
                'severity' => 'high',
                'status' => 'active',
                'description' => 'Gempa bumi dengan magnitudo 6.7 terdeteksi di lepas pantai Banten. Warga diimbau menjauhi pantai karena ada potensi tsunami.',
                'source' => 'manual',
                'raw_data' => ['magnitude' => 6.7, 'depth' => 25],
            ],
            [
                'type' => 'flood',
                'location' => 'Jakarta Timur',
                'latitude' => -6.22,
                'longitude' => 106.90,
                'severity' => 'medium',
                'status' => 'active',
                'description' => 'Banjir setinggi 1-1.5 meter menggenangi sejumlah kelurahan di Jakarta Timur akibat hujan deras dan luapan Kali Ciliwung.',
                'source' => 'manual',
            ],
            [
                'type' => 'flood',
                'location' => 'Semarang, Jawa Tengah',
                'latitude' => -7.00,
                'longitude' => 110.43,
                'severity' => 'high',
                'status' => 'monitoring',
                'description' => 'Banjir rob menggenangi wilayah pesisir Semarang. Ketinggian air mencapai 1.2 meter.',
                'source' => 'manual',
            ],
            [
                'type' => 'volcano',
                'location' => 'Gunung Merapi, DIY',
                'latitude' => -7.54,
                'longitude' => 110.44,
                'severity' => 'medium',
                'status' => 'monitoring',
                'description' => 'Gunung Merapi mengalami peningkatan aktivitas vulkanik. Guguran lava pijar teramati dengan jarak luncur 1.2 km.',
                'source' => 'manual',
            ],
            [
                'type' => 'volcano',
                'location' => 'Gunung Semeru, Lumajang',
                'latitude' => -8.10,
                'longitude' => 112.92,
                'severity' => 'high',
                'status' => 'active',
                'description' => 'Gunung Semeru erupsi dengan letusan setinggi 1 km. Awan panas mengalir hingga jarak 3 km. Status Waspada.',
                'source' => 'manual',
            ],
            [
                'type' => 'landslide',
                'location' => 'Nganjuk, Jawa Timur',
                'latitude' => -7.60,
                'longitude' => 111.90,
                'severity' => 'high',
                'status' => 'active',
                'description' => 'Longsor menimbun jalan dan rumah warga di lereng Gunung Wilis. Tim SAR sedang melakukan evakuasi.',
                'source' => 'manual',
            ],
            [
                'type' => 'tsunami',
                'location' => 'Pangandaran, Jawa Barat',
                'latitude' => -7.69,
                'longitude' => 108.64,
                'severity' => 'critical',
                'status' => 'resolved',
                'description' => 'Tsunami kecil terjadi akibat gempa di lepas pantai. Gelombang setinggi 2-3 meter sempat memasuki daratan.',
                'source' => 'manual',
            ],
            [
                'type' => 'fire',
                'location' => 'Pasar Senen, Jakarta',
                'latitude' => -6.18,
                'longitude' => 106.84,
                'severity' => 'medium',
                'status' => 'resolved',
                'description' => 'Kebakaran di Pasar Senen mengakibatkan kerusakan pada beberapa kios. Api berhasil dipadamkan oleh pemadam kebakaran.',
                'source' => 'manual',
            ],
            [
                'type' => 'drought',
                'location' => 'Sumba Timur, NTT',
                'latitude' => -9.80,
                'longitude' => 120.30,
                'severity' => 'medium',
                'status' => 'monitoring',
                'description' => 'Kekeringan melanda Sumba Timur. Masyarakat kesulitan mendapatkan air bersih. Distribusi air darurat sedang dilakukan.',
                'source' => 'manual',
            ],
        ];

        foreach ($disasters as $disaster) {
            Disaster::create($disaster);
        }
    }
}
