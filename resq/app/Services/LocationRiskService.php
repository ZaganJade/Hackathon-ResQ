<?php

namespace App\Services;

use App\Models\Disaster;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LocationRiskService
{
    /**
     * Radius default untuk pencarian bencana di sekitar user (km)
     */
    private float $defaultRadius = 50;

    /**
     * Status zona berdasarkan jumlah bencana berdekatan waktu
     */
    const STATUS_DANGER = 'danger';     // 10+ bencana berdekatan waktu
    const STATUS_WARNING = 'warning';   // 5-9 bencana berdekatan waktu
    const STATUS_SAFE = 'safe';         // <5 bencana berdekatan waktu

    /**
     * Waktu dekat (dekat waktu) dalam hari untuk dianggap berdekatan waktu
     */
    private int $timeProximityDays = 30;

    /**
     * Analisis status zona berdasarkan lokasi user
     *
     * @param float $lat Latitude user
     * @param float $lng Longitude user
     * @param float|null $radiusKm Radius pencarian (default: 50km)
     * @return array Status zona dan detail analisis
     */
    public function analyzeZoneStatus(float $lat, float $lng, ?float $radiusKm = null): array
    {
        $radius = $radiusKm ?? $this->defaultRadius;
        $timeProximity = $this->timeProximityDays;

        // Ambil bencana aktif di sekitar lokasi user
        $nearbyDisasters = $this->getNearbyActiveDisasters($lat, $lng, $radius);

        // Hitung cluster bencana berdekatan waktu
        $clusters = $this->calculateTimeClusters($nearbyDisasters, $timeProximity);

        // Tentukan status berdasarkan cluster terbesar
        $maxClusterSize = max($clusters) ?? 0;
        $status = $this->determineStatus($maxClusterSize);

        // Hitung statistik tambahan
        $disastersByType = $nearbyDisasters->groupBy('type')->map->count();
        $disastersBySeverity = $nearbyDisasters->groupBy('severity')->map->count();

        // Dapatkan bencana terbaru di sekitar
        $recentDisaster = $nearbyDisasters->sortByDesc('created_at')->first();

        // Format hasil analisis
        return [
            'status' => $status,
            'status_label' => $this->getStatusLabel($status),
            'status_color' => $this->getStatusColor($status),
            'location' => [
                'latitude' => $lat,
                'longitude' => $lng,
                'radius_km' => $radius,
            ],
            'metrics' => [
                'total_nearby_disasters' => $nearbyDisasters->count(),
                'max_cluster_size' => $maxClusterSize,
                'time_proximity_days' => $timeProximity,
                'clusters_count' => count($clusters),
            ],
            'disasters_by_type' => $disastersByType->toArray(),
            'disasters_by_severity' => $disastersBySeverity->toArray(),
            'most_recent_disaster' => $recentDisaster ? [
                'id' => $recentDisaster->id,
                'type' => $recentDisaster->type,
                'severity' => $recentDisaster->severity,
                'location' => $recentDisaster->location,
                'created_at' => $recentDisaster->created_at->toIso8601String(),
                'distance_km' => $this->calculateDistance($lat, $lng, $recentDisaster->latitude, $recentDisaster->longitude),
            ] : null,
            'nearby_disasters' => $nearbyDisasters->map(function ($disaster) use ($lat, $lng) {
                return [
                    'id' => $disaster->id,
                    'type' => $disaster->type,
                    'severity' => $disaster->severity,
                    'location' => $disaster->location,
                    'latitude' => $disaster->latitude,
                    'longitude' => $disaster->longitude,
                    'created_at' => $disaster->created_at->toIso8601String(),
                    'distance_km' => round($this->calculateDistance($lat, $lng, $disaster->latitude, $disaster->longitude), 2),
                ];
            })->toArray(),
            'warning_message' => $this->generateWarningMessage($status, $maxClusterSize, $recentDisaster),
            'recommendations' => $this->getRecommendations($status, $disastersByType->keys()->toArray()),
            'analyzed_at' => Carbon::now()->toIso8601String(),
        ];
    }

    /**
     * Ambil bencana aktif di sekitar lokasi
     * SQLite-compatible dengan filtering jarak di PHP
     */
    private function getNearbyActiveDisasters(float $lat, float $lng, float $radius): \Illuminate\Support\Collection
    {
        $driver = config('database.default');

        // For PostgreSQL, use SQL-level filtering
        if ($driver === 'pgsql') {
            return Disaster::active()
                ->withinRadius($lat, $lng, $radius)
                ->where('created_at', '>=', Carbon::now()->subDays(180))
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // For SQLite: calculate bounding box in PHP, then filter
        // Approximate 1 degree = 111 km
        $latDelta = $radius / 111;
        $lngDelta = $radius / (111 * cos(deg2rad($lat)));

        return Disaster::active()
            ->whereBetween('latitude', [$lat - $latDelta, $lat + $latDelta])
            ->whereBetween('longitude', [$lng - $lngDelta, $lng + $lngDelta])
            ->where('created_at', '>=', Carbon::now()->subDays(180))
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($disaster) use ($lat, $lng, $radius) {
                // Calculate actual distance in PHP for SQLite
                $distance = $this->calculateDistance($lat, $lng, $disaster->latitude, $disaster->longitude);
                return $distance <= $radius;
            });
    }

    /**
     * Hitung cluster bencana berdekatan waktu
     * Menggunakan sliding window approach untuk mencari grup bencana
     * yang terjadi dalam rentang waktu dekat
     */
    private function calculateTimeClusters(\Illuminate\Support\Collection $disasters, int $proximityDays): array
    {
        if ($disasters->isEmpty()) {
            return [0];
        }

        // Sort by date ascending untuk analisis cluster
        $sorted = $disasters->sortBy('created_at')->values();
        $clusters = [];
        $currentCluster = 0;
        $windowStart = null;

        foreach ($sorted as $disaster) {
            $disasterDate = Carbon::parse($disaster->created_at);

            if ($windowStart === null) {
                // Mulai cluster baru
                $windowStart = $disasterDate;
                $currentCluster = 1;
            } else {
                $daysDiff = $windowStart->diffInDays($disasterDate);

                if ($daysDiff <= $proximityDays) {
                    // Masih dalam window yang sama
                    $currentCluster++;
                } else {
                    // Simpan cluster sebelumnya dan mulai baru
                    $clusters[] = $currentCluster;
                    $windowStart = $disasterDate;
                    $currentCluster = 1;
                }
            }
        }

        // Jangan lupa cluster terakhir
        if ($currentCluster > 0) {
            $clusters[] = $currentCluster;
        }

        return $clusters ?: [0];
    }

    /**
     * Tentukan status berdasarkan ukuran cluster terbesar
     */
    private function determineStatus(int $maxClusterSize): string
    {
        return match (true) {
            $maxClusterSize >= 10 => self::STATUS_DANGER,
            $maxClusterSize >= 5 => self::STATUS_WARNING,
            default => self::STATUS_SAFE,
        };
    }

    /**
     * Get label untuk status
     */
    private function getStatusLabel(string $status): string
    {
        return match ($status) {
            self::STATUS_DANGER => 'Zona Berbahaya',
            self::STATUS_WARNING => 'Zona Waspada',
            self::STATUS_SAFE => 'Zona Aman',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Get warna untuk status
     */
    private function getStatusColor(string $status): string
    {
        return match ($status) {
            self::STATUS_DANGER => '#DC2626',   // Red-600
            self::STATUS_WARNING => '#F59E0B',  // Amber-500
            self::STATUS_SAFE => '#10B981',     // Emerald-500
            default => '#6B7280',               // Gray-500
        };
    }

    /**
     * Hitung jarak antara dua koordinat (Haversine formula)
     */
    private function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // km

        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lngDelta / 2) * sin($lngDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Generate pesan peringatan berdasarkan status
     */
    private function generateWarningMessage(string $status, int $clusterSize, ?Disaster $recentDisaster): ?string
    {
        return match ($status) {
            self::STATUS_DANGER => sprintf(
                "PERINGATAN TINGGI: Terdeteksi %d bencana dalam rentang waktu dekat di area Anda. " .
                "Ini menunjukkan pola aktivitas bencana yang signifikan. Disarankan untuk mengikuti protokol evakuasi " .
                "dan memantau informasi bencana terkini.%s",
                $clusterSize,
                $recentDisaster ? " Bencana terbaru: {$recentDisaster->type} di {$recentDisaster->location}." : ""
            ),
            self::STATUS_WARNING => sprintf(
                "Area Waspada: Terdeteksi %d bencana dalam rentang waktu dekat di sekitar lokasi Anda. " .
                "Tetap waspada dan persiapkan diri untuk situasi darurat.%s",
                $clusterSize,
                $recentDisaster ? " Bencana terbaru: {$recentDisaster->type} di {$recentDisaster->location}." : ""
            ),
            self::STATUS_SAFE => $clusterSize > 0
                ? "Area relatif aman dengan aktivitas bencana minimal dalam 6 bulan terakhir."
                : "Tidak ada bencana aktif terdeteksi di area dalam radius 50km.",
            default => null,
        };
    }

    /**
     * Get rekomendasi berdasarkan status dan tipe bencana
     */
    private function getRecommendations(string $status, array $disasterTypes): array
    {
        $generalRecommendations = [
            'danger' => [
                'Segera cari informasi evakuasi dari pihak berwenang',
                'Siapkan bug-out bag (tas darurat) dengan perlengkapan 72 jam',
                'Pastikan jalur evakuasi Anda aman dan dapat diakses',
                'Matikan listrik, gas, dan air sebelum meninggalkan rumah jika diperintahkan',
                'Gunakan aplikasi ResQ untuk panduan evakuasi real-time',
            ],
            'warning' => [
                'Tetap update informasi bencana melalui aplikasi ResQ',
                'Periksa kesiapan darurat keluarga (first aid kit, senter, baterai cadangan)',
                'Diskusikan rencana evakuasi dengan keluarga',
                'Isi ulang persediaan air dan makanan non-perishable',
                'Kenali zona aman terdekat dan jalur evakuasi',
            ],
            'safe' => [
                'Tetap pantau aplikasi ResQ untuk update bencana',
                'Pertahankan kesiapsiagaan dengan kit darurat standar',
                'Bantu sebarkan informasi bencana ke masyarakat sekitar',
                'Ikuti panduan mitigasi bencana yang relevan untuk wilayah Anda',
            ],
        ];

        $typeSpecificRecommendations = [
            'earthquake' => '[GEMPA BUMI] Periksa kekuatan struktur bangunan, kenali titik aman (bawah meja kokoh, sudut dinding interior), pastikan ada jalur evakuasi bebas, dan ikuti latihan evakuasi berkala',
            'flood' => '[BANJIR] Amankan dokumen penting dan elektronik di tempat tinggi, siapkan pelampung/jaket pelampung, kenali jalur evakuasi ke daratan tinggi, dan pantau peringatan dini cuaca ekstrem',
            'tsunami' => '[TSUNAMI] Kenali rute evakuasi ke daratan tinggi terdekat (minimal 3km dari pantai atau 30m ketinggian), kenali tanda alam (gempa kuat, air surut tiba-tiba), jangan tunggu sirine jika gempa terasa kuat',
            'volcanic_eruption' => '[LETUSAN GUNUNG API] Sediakan masker N95/cadar untuk seluruh keluarga, tutup ventilasi saat abu vulkanik, siapkan persediaan air bersih, dan ikuti instruksi evakuasi PVMBG',
            'landslide' => '[TANAH LONGSOR] Hindari area lereng/lereng curam saat hujan deras, perhatikan tanda pergerakan tanah (retakan, pohon miring, suara gemuruh), segera evakuasi jika ada tanda pergerakan',
            'fire' => '[KEBAKARAN] Siapkan pemadam api ringan (APAR) yang masih valid, kenali minimal 2 jalur keluar darurat, jangan gunakan lift saat kebakaran, dan lakukan drill evakuasi berkala',
            'tornado' => '[ANGIN PUTING BELIUNG] Perkuat struktur atap dan jendela, siapkan ruang perlindungan di bagian tengah bangunan (jauh dari jendela), dan pantau peringatan cuaca dari BMKG',
            'drought' => '[KEKERINGAN] Simpan cadangan air minum (minimal 3 hari), hemat air dengan sistem penampungan, dan pantau informasi ketersediaan air dari pemerintah daerah',
            'epidemic' => '[EPIDEMI/WABAH] Ikuti protokol kesehatan, siapkan perlengkapan higiene (masker, hand sanitizer), dan pantau informasi resmi dari Kemenkes',
        ];

        $recommendations = $generalRecommendations[$status] ?? $generalRecommendations['safe'];

        // Tambahkan rekomendasi spesifik berdasarkan tipe bencana di area tersebut
        foreach ($disasterTypes as $type) {
            if (isset($typeSpecificRecommendations[$type])) {
                $recommendations[] = $typeSpecificRecommendations[$type];
            }
        }

        return $recommendations;
    }

    /**
     * Quick check untuk status zona (untuk API yang ringan)
     */
    public function quickZoneStatus(float $lat, float $lng): array
    {
        $disasters = $this->getNearbyActiveDisasters($lat, $lng, $this->defaultRadius);
        $clusters = $this->calculateTimeClusters($disasters, $this->timeProximityDays);
        $maxClusterSize = max($clusters) ?? 0;
        $status = $this->determineStatus($maxClusterSize);

        return [
            'status' => $status,
            'label' => $this->getStatusLabel($status),
            'color' => $this->getStatusColor($status),
            'risk_score' => min(100, $maxClusterSize * 10), // 0-100 scale
            'total_disasters' => $disasters->count(),
        ];
    }

    /**
     * Get trend analisis (apakah risiko naik atau turun)
     */
    public function getRiskTrend(float $lat, float $lng, int $daysBack = 30): array
    {
        $now = Carbon::now();
        $currentPeriodStart = $now->copy()->subDays($daysBack);
        $previousPeriodStart = $now->copy()->subDays($daysBack * 2);

        // Use same filtering approach for SQLite compatibility
        $currentDisasters = $this->getNearbyActiveDisasters($lat, $lng, $this->defaultRadius)
            ->whereBetween('created_at', [$currentPeriodStart, $now])
            ->count();

        $previousDisasters = $this->getNearbyActiveDisasters($lat, $lng, $this->defaultRadius)
            ->whereBetween('created_at', [$previousPeriodStart, $currentPeriodStart])
            ->count();

        $trend = 'stable';
        if ($currentDisasters > $previousDisasters * 1.5) {
            $trend = 'increasing';
        } elseif ($currentDisasters < $previousDisasters * 0.5) {
            $trend = 'decreasing';
        }

        return [
            'trend' => $trend,
            'current_count' => $currentDisasters,
            'previous_count' => $previousDisasters,
            'change_percent' => $previousDisasters > 0
                ? round((($currentDisasters - $previousDisasters) / $previousDisasters) * 100, 1)
                : ($currentDisasters > 0 ? 100 : 0),
        ];
    }
}
