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
            // Earthquakes
            [
                'type' => 'earthquake',
                'location' => 'Cianjur, Jawa Barat',
                'latitude' => -6.78,
                'longitude' => 107.13,
                'severity' => 'high',
                'status' => 'resolved',
                'description' => 'Gempa bumi dengan magnitudo 5.6 mengguncang Cianjur pada November 2022. Ribuan rumah rusak dan ratusan korban jiwa.',
                'source' => 'manual',
                'raw_data' => json_encode(['magnitude' => 5.6, 'depth' => 10, 'felt_reports' => 2340]),
                'resolved_at' => now()->subDays(120),
            ],
            [
                'type' => 'earthquake',
                'location' => 'Banten',
                'latitude' => -6.20,
                'longitude' => 106.00,
                'severity' => 'high',
                'status' => 'active',
                'description' => 'Gempa bumi dengan magnitudo 6.7 terdeteksi di lepas pantai Banten. Warga diimbau menjauhi pantai karena ada potensi tsunami. Ratusan gempa susulan sudah terekam.',
                'source' => 'manual',
                'raw_data' => json_encode(['magnitude' => 6.7, 'depth' => 25, 'felt_reports' => 5600, 'aftershocks' => 142]),
            ],
            [
                'type' => 'earthquake',
                'location' => 'Yogyakarta',
                'latitude' => -7.79,
                'longitude' => 110.37,
                'severity' => 'medium',
                'status' => 'active',
                'description' => 'Serangkaian gempa dengan magnitudo hingga 6.4 terjadi di Yogyakarta. Pemerintah meminta warga hati-hati terhadap gempa susulan.',
                'source' => 'manual',
                'raw_data' => json_encode(['magnitude' => 6.4, 'depth' => 14, 'felt_reports' => 3200, 'aftershocks' => 87]),
            ],
            [
                'type' => 'earthquake',
                'location' => 'Sumatra Utara',
                'latitude' => 3.31,
                'longitude' => 98.67,
                'severity' => 'low',
                'status' => 'monitoring',
                'description' => 'Gempa kecil dengan magnitudo 4.2 terasa di Sumatra Utara. Tidak ada laporan kerusakan signifikan.',
                'source' => 'manual',
                'raw_data' => json_encode(['magnitude' => 4.2, 'depth' => 18, 'felt_reports' => 450]),
            ],

            // Floods
            [
                'type' => 'flood',
                'location' => 'Jakarta Timur',
                'latitude' => -6.22,
                'longitude' => 106.90,
                'severity' => 'medium',
                'status' => 'active',
                'description' => 'Banjir setinggi 1-1.5 meter menggenangi sejumlah kelurahan di Jakarta Timur akibat hujan deras dan luapan Kali Ciliwung. Tim SAR beroperasi untuk mengevakuasi warga.',
                'source' => 'manual',
                'raw_data' => json_encode(['water_height' => '1.2m', 'affected_households' => 2450, 'evacuated' => 1200]),
            ],
            [
                'type' => 'flood',
                'location' => 'Semarang, Jawa Tengah',
                'latitude' => -7.00,
                'longitude' => 110.43,
                'severity' => 'high',
                'status' => 'monitoring',
                'description' => 'Banjir rob menggenangi wilayah pesisir Semarang. Ketinggian air mencapai 1.2 meter. Transportasi terganggu, sekolah diliburkan.',
                'source' => 'manual',
                'raw_data' => json_encode(['water_height' => '1.2m', 'affected_households' => 3100, 'salt_intrusion' => true]),
            ],
            [
                'type' => 'flood',
                'location' => 'Banjarmasin, Kalimantan Selatan',
                'latitude' => -3.32,
                'longitude' => 114.59,
                'severity' => 'high',
                'status' => 'active',
                'description' => 'Musim hujan membawa banjir ke Banjarmasin. Beberapa kecamatan terendam. Logistik kemanusiaan sedang didistribusikan.',
                'source' => 'manual',
                'raw_data' => json_encode(['water_height' => '1.8m', 'affected_households' => 5300, 'evacuated' => 2100]),
            ],

            // Volcanoes
            [
                'type' => 'volcano',
                'location' => 'Gunung Merapi, DIY',
                'latitude' => -7.54,
                'longitude' => 110.44,
                'severity' => 'medium',
                'status' => 'monitoring',
                'description' => 'Gunung Merapi mengalami peningkatan aktivitas vulkanik. Guguran lava pijar teramati dengan jarak luncur 1.2 km. Status diperbaharui menjadi Siaga.',
                'source' => 'manual',
                'raw_data' => json_encode(['alert_level' => 'III_SIAGA', 'evacuation_radius' => '5km', 'seismic_events' => 234, 'lava_flow_distance' => '1.2km']),
            ],
            [
                'type' => 'volcano',
                'location' => 'Gunung Semeru, Lumajang',
                'latitude' => -8.10,
                'longitude' => 112.92,
                'severity' => 'high',
                'status' => 'active',
                'description' => 'Gunung Semeru erupsi dengan letusan setinggi 1 km. Awan panas mengalir hingga jarak 3 km. Status Waspada (Level 2). Ratusan warga dievakuasi.',
                'source' => 'manual',
                'raw_data' => json_encode(['alert_level' => 'II_WASPADA', 'eruption_height' => '1000m', 'hot_flow_distance' => '3km', 'evacuated_count' => 890]),
            ],
            [
                'type' => 'volcano',
                'location' => 'Gunung Sinabung, Sumatra Utara',
                'latitude' => 3.17,
                'longitude' => 98.39,
                'severity' => 'medium',
                'status' => 'monitoring',
                'description' => 'Gunung Sinabung menunjukkan aktivitas vulkanik meningkat. Penduduk diminta waspada terhadap potensi erupsi.',
                'source' => 'manual',
                'raw_data' => json_encode(['alert_level' => 'II_WASPADA', 'seismic_events' => 156, 'visitors_banned' => true]),
            ],

            // Landslides
            [
                'type' => 'landslide',
                'location' => 'Nganjuk, Jawa Timur',
                'latitude' => -7.60,
                'longitude' => 111.90,
                'severity' => 'high',
                'status' => 'active',
                'description' => 'Longsor menimbun jalan dan rumah warga di lereng Gunung Wilis. Tim SAR sedang melakukan evakuasi. Akses jalan ke desa terisolasi.',
                'source' => 'manual',
                'raw_data' => json_encode(['affected_households' => 45, 'casualties' => 8, 'missing' => 3, 'buried_area' => '2.5 hectares']),
            ],
            [
                'type' => 'landslide',
                'location' => 'Bandung, Jawa Barat',
                'latitude' => -6.90,
                'longitude' => 107.61,
                'severity' => 'medium',
                'status' => 'monitoring',
                'description' => 'Potensi longsor terdeteksi di sejumlah area di Bandung. Pemerintah membuat sistem peringatan dini untuk masyarakat.',
                'source' => 'manual',
                'raw_data' => json_encode(['risk_areas' => 7, 'residents_at_risk' => 1200, 'early_warning_installed' => true]),
            ],

            // Tsunamis
            [
                'type' => 'tsunami',
                'location' => 'Pangandaran, Jawa Barat',
                'latitude' => -7.69,
                'longitude' => 108.64,
                'severity' => 'critical',
                'status' => 'resolved',
                'description' => 'Tsunami kecil terjadi akibat gempa di lepas pantai. Gelombang setinggi 2-3 meter sempat memasuki daratan. Wisata ditutup sementara.',
                'source' => 'manual',
                'raw_data' => json_encode(['wave_height' => '2.5m', 'affected_area' => '3km', 'evacuated' => 450]),
                'resolved_at' => now()->subDays(60),
            ],
            [
                'type' => 'tsunami',
                'location' => 'Palu, Sulawesi Tengah',
                'latitude' => -0.89,
                'longitude' => 119.83,
                'severity' => 'critical',
                'status' => 'resolved',
                'description' => 'Tsunami setinggi 5-7 meter menghampir pantai Palu. Ribuan bangunan rusak dan ratusan orang hilang.',
                'source' => 'manual',
                'raw_data' => json_encode(['wave_height' => '6m', 'casualties' => 2081, 'affected_households' => 67847]),
                'resolved_at' => now()->subDays(300),
            ],

            // Fires
            [
                'type' => 'fire',
                'location' => 'Pasar Senen, Jakarta',
                'latitude' => -6.18,
                'longitude' => 106.84,
                'severity' => 'medium',
                'status' => 'resolved',
                'description' => 'Kebakaran di Pasar Senen mengakibatkan kerusakan pada beberapa kios. Api berhasil dipadamkan oleh pemadam kebakaran setelah 4 jam.',
                'source' => 'manual',
                'raw_data' => json_encode(['duration_hours' => 4, 'shops_damaged' => 23, 'firefighters_deployed' => 15, 'cause' => 'short_circuit']),
                'resolved_at' => now()->subDays(45),
            ],
            [
                'type' => 'fire',
                'location' => 'Hutan Riau',
                'latitude' => 0.50,
                'longitude' => 101.44,
                'severity' => 'high',
                'status' => 'active',
                'description' => 'Kebakaran hutan di Riau menyebar cepat. Asap tebal menggangu visibilitas dan kualitas udara di wilayah sekitar. Tim pemadam kebakaran terus bergerak.',
                'source' => 'manual',
                'raw_data' => json_encode(['burned_area' => '12500 hectares', 'air_quality_index' => 'hazardous', 'firefighting_aircraft' => 5]),
            ],

            // Droughts
            [
                'type' => 'drought',
                'location' => 'Sumba Timur, NTT',
                'latitude' => -9.80,
                'longitude' => 120.30,
                'severity' => 'medium',
                'status' => 'monitoring',
                'description' => 'Kekeringan melanda Sumba Timur. Masyarakat kesulitan mendapatkan air bersih. Distribusi air darurat sedang dilakukan oleh BPBD.',
                'source' => 'manual',
                'raw_data' => json_encode(['affected_villages' => 12, 'affected_population' => 8900, 'water_truck_deployed' => 3, 'rainfall_mm' => 8]),
            ],
            [
                'type' => 'drought',
                'location' => 'Lombok, NTB',
                'latitude' => -8.67,
                'longitude' => 116.31,
                'severity' => 'medium',
                'status' => 'active',
                'description' => 'Musim kemarau panjang menyebabkan kekeringan di Lombok. Petani mengalami gagal panen. Krisis air menimpa penduduk desa.',
                'source' => 'manual',
                'raw_data' => json_encode(['days_without_rain' => 156, 'crop_failure_area' => '4200 hectares', 'affected_farmers' => 2340, 'water_sources_dried' => 45]),
            ],
        ];

        foreach ($disasters as $disaster) {
            Disaster::create($disaster);
        }
    }
}
