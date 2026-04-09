## Why

User perlu tahu status risiko bencana di lokasi mereka secara real-time agar bisa bersiap dan mengambil tindakan mitigasi yang tepat. Sistem saat ini tidak memiliki mekanisme untuk mendeteksi lokasi user dan memberikan peringatan personal berdasarkan data bencana di sekitar mereka.

## What Changes

- **LocationRiskService**: Service baru untuk analisis status zona (Merah/Waspada/Hijau) berdasarkan clustering bencana berdekatan waktu
- **AI Chat dengan Konteks Lokasi**: Integrasi lokasi ke AI Assist agar AI bisa memberikan peringatan personal dan rekomendasi berbasis lokasi
- **API Endpoints Lokasi**: REST API untuk pengecekan status zona, bencana di sekitar, dan reverse geocoding
- **Frontend Geolocation**: Komponen untuk akses lokasi browser (geolocation API) dengan indikator status zona
- **Database User Locations**: Tabel untuk menyimpan lokasi tersimpan user (rumah, kantor, dll)
- **Console Command**: Command terjadwal untuk monitoring risiko dan notifikasi otomatis

## Capabilities

### New Capabilities
- `location-risk-analysis`: Analisis status zona berdasarkan riwayat bencana dengan algoritma clustering waktu
- `location-aware-ai`: AI chatbot yang aware lokasi user dan bisa memberikan peringatan personal
- `user-location-management`: Manajemen lokasi tersimpan user (CRUD lokasi favorit)
- `geolocation-api`: API endpoints untuk pengecekan status zona dan bencana di sekitar

### Modified Capabilities
- `ai-assist`: Extend untuk support chat dengan konteks lokasi (parameter lat/lng opsional)

## Impact

**Backend (PHP/Laravel)**:
- New: `app/Services/LocationRiskService.php`
- New: `app/Models/UserLocation.php`
- New: `app/Http/Controllers/LocationRiskController.php`
- New: `app/Console/Commands/CheckLocationRiskCommand.php`
- Modified: `app/Services/AIAssistService.php` (tambah `chatWithLocation()`)
- Migration: `create_user_locations_table.php`

**API**:
- New endpoints di `routes/api.php`:
  - `GET /api/v1/location/status` - Quick zone status
  - `POST /api/v1/location/analyze` - Full zone analysis
  - `GET /api/v1/location/nearby-disasters` - Bencana di radius tertentu
  - `GET /api/v1/location/reverse-geocode` - Koordinat ke alamat
  - `POST /api/v1/location/chat` - Chat AI dengan lokasi

**Frontend (Blade/Alpine.js)**:
- New component: `resources/views/components/zone-status-widget.blade.php`
- Modified: `resources/views/components/ai-chatbot.blade.php` (tambah geolocation)
- Modified: `resources/views/dashboard.blade.php` (tambah widget zona)

**Scheduled Tasks**:
- `resq:check-location-risk` command (setiap 30 menit)
- Daily notification summary pukul 08:00

**Browser Permissions**:
- Memerlukan izin geolocation dari user
- CSP headers sudah support (Permissions-Policy geolocation=(self))

## Success Criteria

- [ ] User bisa melihat status zona mereka di dashboard (Merah/Waspada/Hijau)
- [ ] AI chatbot memberikan peringatan saat user di zona berbahaya
- [ ] API bisa diakses dengan lat/lng untuk mendapatkan status zona
- [ ] Scheduled command berjalan tanpa error
- [ ] Notifikasi terkirim ke user di zona danger/warning
