# ResQ WhatsApp Webhook API Documentation

Dokumentasi lengkap untuk integrasi WhatsApp webhook ResQ dengan pihak ketiga (e.g., BMKG).

---

## 🔐 Autentikasi

Semua endpoint webhook memerlukan header `X-API-Key`:

```
X-API-Key: resq_webhook_key_2024_bmkg
```

---

## 📡 Base URL

```
http://localhost:8000/api/v1/webhook
```

Untuk production:
```
https://your-domain.com/api/v1/webhook
```

---

## 🚀 Endpoints

### 1. Kirim Pesan ke Satu Nomor

**Endpoint:** `POST /whatsapp/send`

Kirim pesan WhatsApp ke satu nomor tujuan.

#### Request:

```bash
curl -X POST http://localhost:8000/api/v1/webhook/whatsapp/send \
  -H "X-API-Key: resq_webhook_key_2024_bmkg" \
  -H "Content-Type: application/json" \
  -d '{
    "phone": "62895341414271",
    "message": "Peringatan gempa magnitude 6.0 terdeteksi di Jakarta!",
    "disaster_type": "earthquake",
    "location": "Jakarta Pusat",
    "severity": "high"
  }'
```

#### Parameters:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `phone` | string | ✅ | Nomor WhatsApp (format: 628xxxxxxxxxx) |
| `message` | string | ✅ | Isi pesan notifikasi |
| `disaster_type` | string | ❌ | Jenis bencana: `earthquake`, `flood`, `tsunami`, `landslide`, `volcano`, `fire` |
| `location` | string | ❌ | Lokasi bencana |
| `severity` | string | ❌ | Tingkat keparahan: `low`, `medium`, `high`, `critical` |

#### Response Success (200):

```json
{
  "success": true,
  "data": {
    "message_id": "447",
    "phone": "62895341414271",
    "status": "sent",
    "sent_at": "2026-04-09T06:46:39+07:00",
    "provider": "yobase"
  },
  "meta": {
    "response_time_ms": 3992.9,
    "timestamp": "2026-04-09T06:46:39+07:00"
  }
}
```

#### Response Error (422 - Invalid Phone):

```json
{
  "success": false,
  "error": "Invalid phone number format. Use Indonesian format (e.g., 628123456789).",
  "phone_received": "089696578125",
  "phone_normalized": "6289696578125"
}
```

---

### 2. Broadcast ke Semua User Terdaftar

**Endpoint:** `POST /whatsapp/broadcast`

Kirim pesan WhatsApp ke **semua user aktif** yang terdaftar di database.

#### Request:

```bash
curl -X POST http://localhost:8000/api/v1/webhook/whatsapp/broadcast \
  -H "X-API-Key: resq_webhook_key_2024_bmkg" \
  -H "Content-Type: application/json" \
  -d '{
    "message": "Peringatan gempa magnitude 6.0 terdeteksi di Jakarta! Segera evakuasi ke tempat aman.",
    "disaster_type": "earthquake",
    "location": "Jakarta Pusat",
    "severity": "high",
    "filter_type": "earthquake"
  }'
```

#### Parameters:

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `message` | string | ✅ | Isi pesan notifikasi |
| `disaster_type` | string | ❌ | Jenis bencana |
| `location` | string | ❌ | Lokasi bencana |
| `severity` | string | ❌ | Tingkat keparahan |
| `filter_type` | string | ❌ | Filter user yang subscribe disaster type tertentu |

#### Response Success (200):

```json
{
  "success": true,
  "data": {
    "broadcast_id": "bc_6614f8a5c4e1a",
    "summary": {
      "total_recipients": 5,
      "sent": 5,
      "failed": 0,
      "success_rate": "100%"
    },
    "details": [
      {"phone": "62895341414271", "status": "sent", "message_id": "448"},
      {"phone": "6281234567890", "status": "sent", "message_id": "449"}
    ]
  },
  "meta": {
    "timestamp": "2026-04-09T07:00:00+07:00"
  }
}
```

#### Response Error (404 - No Recipients):

```json
{
  "success": false,
  "error": "No active recipients found with WhatsApp numbers."
}
```

#### Response Error (429 - Rate Limit):

```json
{
  "success": false,
  "error": "Broadcast rate limit exceeded. Max 10 broadcasts per hour.",
  "retry_after": 1800
}
```

---

### 3. Cek Status WhatsApp Service

**Endpoint:** `GET /whatsapp/status`

Cek status koneksi WhatsApp service.

#### Request:

```bash
curl -X GET http://localhost:8000/api/v1/webhook/whatsapp/status
```

#### Response Success (200):

```json
{
  "success": true,
  "data": {
    "service": "whatsapp",
    "provider": "yobase",
    "status": "connected",
    "account": "628xxxxxx",
    "timestamp": "2026-04-09T07:00:00+07:00"
  }
}
```

---

### 4. Health Check

**Endpoint:** `GET /health`

Cek status API secara umum (no auth required).

#### Request:

```bash
curl -X GET http://localhost:8000/api/health
```

#### Response:

```json
{
  "status": "ok",
  "service": "ResQ API",
  "timestamp": "2026-04-09T07:00:00+07:00"
}
```

---

## 📊 Rate Limiting

| Endpoint | Limit |
|----------|-------|
| `POST /whatsapp/send` | 60 requests per minute |
| `POST /whatsapp/broadcast` | 10 requests per hour |

---

## 🔧 Format Nomor Telepon

Format yang diterima:
- `62895341414271` ✅ (Recommended)
- `0895341414271` ✅ (Otomatis dikonversi)
- `+62895341414271` ✅ (Otomatis dikonversi)

**Panjang nomor:** 10-14 digit (support nomor Indonesia)

---

## 📝 Contoh Penggunaan dengan File JSON

Untuk pesan yang panjang, simpan di file JSON:

**1. Buat file `broadcast.json`:**

```json
{
  "message": "Peringatan gempa bumi dengan magnitude 6.0 telah terdeteksi di wilayah Jakarta dan sekitarnya. Segera lakukan evakuasi ke tempat yang aman dan jauhi bangunan tinggi.",
  "disaster_type": "earthquake",
  "location": "Jakarta dan Sekitarnya",
  "severity": "high",
  "filter_type": "earthquake"
}
```

**2. Kirim request:**

```bash
curl -X POST http://localhost:8000/api/v1/webhook/whatsapp/broadcast \
  -H "X-API-Key: resq_webhook_key_2024_bmkg" \
  -H "Content-Type: application/json" \
  -d @broadcast.json
```

---

## ⚙️ Konfigurasi Environment

File `.env`:

```env
# WhatsApp Configuration (Yobase)
WHATSAPP_PROVIDER=yobase
WHATSAPP_API_URL=https://whats.yobase.me/api
WHATSAPP_API_TOKEN=fa772c899aa5ddc7a8a131019c3e984bd9dc7773ed532618964833f81e115798
WHATSAPP_SENDER_NUMBER=sess_40_1775684572
WHATSAPP_TIMEOUT=30
WHATSAPP_MAX_RETRIES=3
WHATSAPP_RETRY_DELAY=2000
WHATSAPP_BULK_BATCH_SIZE=100

# Webhook API Key
WEBHOOK_API_KEY=resq_webhook_key_2024_bmkg
```

---

## 🐛 Error Codes

| HTTP Code | Meaning |
|-----------|---------|
| `200` | Success |
| `401` | Unauthorized - API Key salah atau tidak ada |
| `404` | Not Found - Tidak ada recipient |
| `422` | Validation Error - Data tidak valid |
| `429` | Rate Limit Exceeded - Terlalu banyak request |
| `500` | Server Error - Gagal mengirim pesan |
| `503` | Service Unavailable - WhatsApp service down |

---

## 📞 Provider WhatsApp

**Current Provider:** [Yobase](https://yobase.me)

**Alternative Providers:**
- Wablas (https://wablas.com)
- Twilio (https://twilio.com/whatsapp)
- MessageBird (https://messagebird.com)

---

## 🔗 Integrasi BMKG

Contoh integrasi dengan data gempa BMKG:

```bash
#!/bin/bash

# Data dari BMKG
MAG="6.0"
LOC="Jakarta"
DEPTH="10"

# Kirim broadcast
curl -X POST http://localhost:8000/api/v1/webhook/whatsapp/broadcast \
  -H "X-API-Key: resq_webhook_key_2024_bmkg" \
  -H "Content-Type: application/json" \
  -d "{
    \"message\": \"Gempa Bumi terdeteksi! Magnitude: $MAG, Kedalaman: ${DEPTH}km. Segera evakuasi ke tempat aman dan waspada potensi aftershock.\",
    \"disaster_type\": \"earthquake\",
    \"location\": \"$LOC\",
    \"severity\": \"high\"
  }"
```

---

## 📝 Changelog

### v1.0.0 (2026-04-09)
- ✅ Initial release
- ✅ Single send endpoint
- ✅ Broadcast endpoint
- ✅ Status check endpoint
- ✅ Yobase integration
- ✅ Phone validation (10-14 digit)
- ✅ Rate limiting

---

**Dibuat oleh:** ResQ Development Team  
**Terakhir update:** 9 April 2026
