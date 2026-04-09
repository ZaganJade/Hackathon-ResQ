## Context

Sistem ResQ saat ini memiliki data bencana aktif tapi tidak ada mekanisme untuk memberikan peringatan personal berdasarkan lokasi user. User perlu login ke dashboard dan melihat peta untuk mengetahui risiko di area mereka. Dengan fitur ini, user akan otomatis mendapat informasi status zona mereka saat membuka aplikasi.

## Goals / Non-Goals

**Goals:**
- Deteksi lokasi user via browser geolocation API
- Analisis status zona (Danger/Warning/Safe) berdasarkan clustering bencana berdekatan waktu
- Tampilkan status zona di dashboard dengan indikator visual
- AI chatbot yang aware lokasi dan bisa memberikan peringatan personal
- API endpoints untuk akses data berbasis lokasi
- Notifikasi otomatis ke user di zona berbahaya

**Non-Goals:**
- Real-time tracking lokasi user (hanya check saat akses/scheduled)
- Prediksi bencana (hanya analisis historis dan aktif)
- Integrasi dengan perangkat IoT/sensor
- Mobile app native (fitur ini untuk web dulu)

## Decisions

### 1. Algoritma Clustering untuk Status Zona
**Keputusan**: Menggunakan sliding window approach untuk deteksi bencana berdekatan waktu
- **Rationale**: Sederhana dan efektif untuk mendeteksi pola aktivitas bencana dalam rentang waktu tertentu
- **Parameters**:
  - Time proximity: 30 hari (bencana dalam 30 hari dianggap berdekatan waktu)
  - Danger threshold: 10+ bencana berdekatan waktu
  - Warning threshold: 5-9 bencana berdekatan waktu
  - Safe: <5 bencana berdekatan waktu
  - Radius default: 50km

**Alternatives considered**:
- Machine learning clustering: Terlalu kompleks untuk MVP, butuh data training
- Simple count tanpa time window: Tidak akurat karena bencana tahun lalu tidak relevan

### 2. Storage Lokasi User
**Keputusan**: Simpan lokasi user di tabel terpisah (user_locations) dengan support multiple locations
- **Rationale**: User bisa punya beberapa lokasi penting (rumah, kantor, kos) dan ingin monitoring semua
- **Fields**: name, lat, lng, address, is_default, notifications_enabled, radius_km

**Alternatives considered**:
- Simpan di users table saja: Tidak fleksibel untuk multiple locations
- Hanya geolocation realtime: Tidak bisa scheduled monitoring untuk lokasi penting

### 3. Integrasi AI dengan Lokasi
**Keputusan**: Extend existing AIAssistService dengan parameter lokasi opsional
- **Rationale**: Maintain backward compatibility, chat biasa tetap works
- **Approach**: Method baru `chatWithLocation()` yang inject konteks zona ke system prompt
- **Fallback**: Jika lokasi tidak tersedia, tetap bisa chat biasa

**Alternatives considered**:
- Pisah service baru: Tidak perlu, cuma extend existing functionality
- Require lokasi untuk semua chat: Mengurangi UX untuk user yang tidak mau share lokasi

### 4. Frontend Geolocation
**Keputusan**: Gunakan browser Geolocation API dengan Alpine.js
- **Rationale**: Native support di modern browser, tidak perlu library eksternal
- **Permission handling**: Request on demand saat user buka chat/dashboard
- **Privacy**: Hanya store di memory (Alpine data), tidak persist ke server kecuali user save location

**Alternatives considered**:
- IP-based geolocation: Kurang akurat untuk peringatan bencana
- Google Maps Geolocation API: Butuh API key dan billing

### 5. API Rate Limiting untuk Location Endpoints
**Keputusan**: No specific rate limiting khusus untuk location endpoints, gunakan default Laravel
- **Rationale**: Endpoints ini read-only dan cache-friendly, tidak memerlukan protection khusus
- **Revisit**: Jika ada abuse, tambahkan rate limiting per IP

## Risks / Trade-offs

| Risk | Mitigation |
|------|------------|
| User tidak grant permission geolocation | Fallback to saved locations atau manual input; tampilkan instruksi cara enable |
| Inaccurate location dari browser | Validasi koordinat (range check); user bisa manual save location sebagai backup |
| Performance issue dengan banyak bencana | Query optimized dengan index lat/lng; Haversine formula di PostgreSQL native |
| False positive/negative zona | Parameter threshold bisa di-tune via config; human review untuk zona danger |
| Privacy concern dengan lokasi user | Hanya akses saat user aktif di aplikasi; tidak track background; user bisa disable |
| Browser tidak support geolocation | Feature detection dan graceful degradation; user bisa input manual |

## Migration Plan

### Deployment Steps:
1. Run migration `create_user_locations_table`
2. Deploy kode baru (service, controller, components)
3. Test API endpoints dengan Postman/curl
4. Monitor error logs untuk geolocation errors
5. Enable scheduled command setelah stabil

### Rollback Strategy:
- Revert kode ke versi sebelumnya
- Migration aman (tabel baru, tidak ada alter existing)
- User experience: Chat AI tetap works tanpa lokasi (backward compatible)

### Database Migration:
```bash
php artisan migrate --path=database/migrations/2026_04_09_085035_create_user_locations_table.php
```

## Open Questions

1. **Threshold zona**: Apakah parameter 10/5/5 bencana dan 30 hari time window cocok untuk Indonesia? Perlu tuning berdasarkan feedback awal.

2. **Notifikasi channel**: Saat ini hanya WhatsApp. Apakah perlu email/SMS juga untuk zona danger?

3. **Offline support**: Apakah perlu simpan lokasi dan status zona di localStorage untuk akses offline?

4. **Tampilkan zona di map**: Apakah perlu overlay zona danger/warning di peta interaktif?
