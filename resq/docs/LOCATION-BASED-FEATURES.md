# Location-Based Disaster Risk Analysis

Sistem analisis risiko bencana berbasis lokasi yang memberikan peringatan dini dan informasi mitigasi berdasarkan lokasi pengguna.

## Overview

Fitur ini menganalisis data bencana di sekitar lokasi pengguna dan memberikan status zona (Aman/Waspada/Berbahaya) beserta rekomendasi mitigasi yang relevan.

## Key Features

- **Zone Status Widget**: Komponen dashboard yang menampilkan status zona berdasarkan lokasi saat ini
- **AI Chat dengan Lokasi**: Chatbot yang memberikan rekomendasi spesifik berdasarkan status zona
- **Saved Locations**: Pengguna dapat menyimpan multiple lokasi (rumah, kantor, dll)
- **WhatsApp Notifications**: Peringatan otomatis saat zona berubah menjadi berbahaya
- **API Endpoints**: REST API untuk integrasi dengan aplikasi mobile

## Algorithm Overview

### Clustering Algorithm (Sliding Window)

Sistem menggunakan sliding window algorithm untuk mengelompokkan bencana yang terjadi dalam rentang waktu dekat:

```
Input: List bencana aktif dalam radius X km
Output: Array ukuran cluster

1. Sort bencana by date (ascending)
2. Initialize: windowStart = null, currentCluster = 0
3. For each disaster:
   - If windowStart is null OR daysDiff > proximityDays:
     * Save current cluster
     * Start new window
   - Else: Increment current cluster
4. Return array of cluster sizes
```

### Status Thresholds

| Status | Cluster Size | Warna | Deskripsi |
|--------|-------------|-------|-----------|
| Safe | < 5 disasters | Hijau (#10B981) | Aktivitas bencana minimal |
| Warning | 5-9 disasters | Kuning (#F59E0B) | Tingkat waspada |
| Danger | 10+ disasters | Merah (#DC2626) | Zona berbahaya |

### Default Parameters

- **Search Radius**: 50 km (dapat dikustomisasi per lokasi)
- **Time Window**: 30 hari (bencana dalam 30 hari terakhir)
- **Cluster Proximity**: 30 hari (bencana dianggap berdekatan waktu jika < 30 hari)
- **History Lookback**: 180 hari (6 bulan data bencana)

## Components

### 1. Zone Status Widget (`resources/views/components/zone-status-widget.blade.php`)

Komponen Alpine.js yang:
- Meminta izin geolocation dari browser
- Menampilkan status zona dengan indikator warna
- Menunjukkan jumlah bencana di sekitar
- Menampilkan rekomendasi mitigasi
- Memungkinkan refresh lokasi manual

States:
- `loading`: Mendeteksi lokasi
- `requesting`: Meminta izin geolocation
- `denied`: Izin ditolak user
- `error`: Error saat fetch data
- `active`: Menampilkan status zona

### 2. LocationRiskService (`app/Services/LocationRiskService.php`)

Service utama yang menangani:
- `analyzeZoneStatus()`: Analisis lengkap dengan semua metrics
- `quickZoneStatus()`: Check cepat untuk widget
- `calculateTimeClusters()`: Sliding window clustering
- `getRecommendations()`: Rekomendasi spesifik tipe bencana

### 3. UserLocation Model (`app/Models/UserLocation.php`)

Model untuk menyimpan lokasi pengguna:

```php
$user->locations();           // All saved locations
$user->defaultLocation();     // Default location
$location->notificationsEnabled(); // Scope untuk notifikasi aktif
```

Fields:
- `name`: Nama lokasi (Rumah, Kantor, dll)
- `latitude`, `longitude`: Koordinat
- `address`: Alamat lengkap
- `is_default`: Lokasi default
- `notifications_enabled`: Aktifkan notifikasi
- `notification_radius_km`: Radius notifikasi (default: 50)

### 4. API Endpoints

| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/api/v1/location/status` | GET | Yes | Quick status check |
| `/api/v1/location/analyze` | POST | Yes | Full analysis |
| `/api/v1/location/nearby-disasters` | GET | Yes | List bencana terdekat |
| `/api/v1/location/reverse-geocode` | GET | Yes | Convert lat/lng ke alamat |
| `/api/v1/location/chat` | POST | Yes | AI chat dengan konteks lokasi |

### 5. Console Command

```bash
# Check all users with saved locations
php artisan resq:check-location-risk

# Check specific user
php artisan resq:check-location-risk --user=123

# Check and send notifications
php artisan resq:check-location-risk --notify
```

Scheduled tasks:
- Every 30 minutes: Check location risks
- Daily at 08:00: Check with notifications (`--notify`)

## Type-Specific Recommendations

Setiap tipe bencana memiliki rekomendasi mitigasi spesifik:

### Earthquake (Gempa Bumi)
- Drop, Cover, and Hold On
- Hindari jendela dan benda yang bisa jatuh
- Tetap di dalam sampai guncangan berhenti
- Siapkan emergency kit

### Flood (Banjir)
- Segera evakuasi ke dataran tinggi
- Hindari berjalan di air mengalir
- Matikan listrik sebelum meninggalkan rumah
- Simpan dokumen penting di tempat tinggi

### Tsunami
- Segera lari ke area yang lebih tinggi (> 30m dari permukaan laut)
- Hindari pantai meski air surut
- Tunggu informasi resmi sebelum kembali
- Ikuti rute evakuasi yang ditandai

### Volcanic Eruption (Letusan Gunung Berapi)
- Gunakan masker N95 untuk abu vulkanik
- Evakuasi menjauh dari lereng gunung
- Perhatikan jalur evakuasi resmi
- Tutup ventilasi dan jendela

### Fire (Kebakaran)
- Tutup hidung dengan kain basah
- Cari jalur keluar terdekat (jangan gunakan lift)
- Merangkak jika ada asap tebal
- Hubungi pemadam kebakaran (113)

## Security & Privacy

### CSP Headers

Untuk geolocation API, pastikan CSP headers mengizinkan:
```
script-src 'self' 'unsafe-inline';
connect-src 'self' https://api.resq.id;
```

### Data Privacy

- Koordinat lokasi user dienkripsi di database
- Lokasi hanya digunakan untuk analisis risiko
- User dapat menonaktifkan notifikasi kapan saja
- Tidak ada tracking lokasi real-time

## Testing

```bash
# Unit tests for LocationRiskService
php artisan test tests/Unit/Services/LocationRiskServiceTest.php

# Feature tests for API endpoints
php artisan test tests/Feature/LocationRiskApiTest.php

# Test the console command
php artisan resq:check-location-risk --user=1
```

## Troubleshooting

### Geolocation not working
1. Pastikan browser mengizinkan akses lokasi
2. Periksa CSP headers di browser console
3. Coba refresh halaman dan izinkan kembali

### Widget shows "denied" status
- User perlu mengizinkan akses lokasi di browser
- Check browser permission settings untuk site ini

### Notifications not sending
- Verifikasi user memiliki `notifications_enabled = true`
- Check WhatsApp service health: `php artisan api:health-check`
- Pastikan user memiliki phone number tersimpan

## Related Files

- `app/Services/LocationRiskService.php` - Core algorithm
- `app/Services/AIAssistService.php` - AI integration dengan lokasi
- `app/Console/Commands/CheckLocationRiskCommand.php` - Scheduled checks
- `resources/views/components/zone-status-widget.blade.php` - Dashboard widget
- `resources/views/components/ai-chatbot.blade.php` - Chatbot dengan lokasi
- `routes/api.php` - API route definitions
