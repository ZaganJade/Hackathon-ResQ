<?php

namespace App\Services;

use App\Models\Disaster;
use App\Models\NotificationLog;
use App\Models\NotificationPreference;
use App\Models\User;
use App\Jobs\SendWhatsAppNotificationJob;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class NotificationService
{
    private string $apiUrl;
    private string $apiToken;
    private string $senderNumber;
    private int $maxRetries = 3;
    private int $rateLimit = 20; // messages per minute

    public function __construct()
    {
        $this->apiUrl       = config('services.whatsapp.api_url', '');
        $this->apiToken     = config('services.whatsapp.api_token', '');
        $this->senderNumber = config('services.whatsapp.sender_number', '');
    }

    // ----------------------------------------------------------------
    // 9.1 / 9.2  Core send via HTTP client
    // ----------------------------------------------------------------

    /**
     * Send a WhatsApp message directly (synchronous).
     *
     * @return array{success: bool, message_id: string|null, error: string|null}
     */
    public function sendMessage(string $phoneNumber, string $message, int $attempt = 1): array
    {
        try {
            // Use config timeout (default 30s) with longer connect timeout for DNS resolving
            $timeout = config('services.whatsapp.timeout', 30);

            $response = Http::timeout($timeout)
                ->connectTimeout(20) // Allow up to 20s for DNS/connect
                ->retry(3, 2000, function ($exception, $request) {
                    // Retry on connection/DNS errors
                    return $exception instanceof \Illuminate\Http\Client\ConnectionException;
                })
                ->withHeaders([
                    'X-Api-Key' => $this->apiToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Host' => 'whats.yobase.me', // Required when using IP address directly
                ])
                ->post($this->apiUrl . '/send', [
                    'session_id' => $this->senderNumber,
                    'to'         => $phoneNumber,
                    'message'    => $message,
                ]);

            if ($response->successful()) {
                return [
                    'success'    => true,
                    'message_id' => $response->json('data.id'), // Yobase returns id in data object
                    'error'      => null,
                ];
            }

            // Retry on server errors (5xx) if we haven't exceeded max retries
            if ($response->serverError() && $attempt < $this->maxRetries) {
                // Exponential backoff: 0.5s, 1s, 2s
                usleep(500 * (2 ** ($attempt - 1)) * 1000);
                return $this->sendMessage($phoneNumber, $message, $attempt + 1);
            }

            return [
                'success'    => false,
                'message_id' => null,
                'error'      => 'HTTP ' . $response->status() . ': ' . $response->body(),
            ];
        } catch (\Throwable $e) {
            Log::error('WhatsApp API error', ['error' => $e->getMessage(), 'phone' => $phoneNumber, 'attempt' => $attempt]);

            // Retry on network errors if we haven't exceeded max retries
            if ($attempt < $this->maxRetries) {
                usleep(500 * (2 ** ($attempt - 1)) * 1000);
                return $this->sendMessage($phoneNumber, $message, $attempt + 1);
            }

            return [
                'success'    => false,
                'message_id' => null,
                'error'      => $e->getMessage(),
            ];
        }
    }

    /**
     * Send WhatsApp message to a phone number (wrapper for sendMessage).
     * Used by CheckLocationRiskCommand for location-based risk notifications.
     */
    public function sendWhatsApp(string $phoneNumber, string $message): array
    {
        return $this->sendMessage($phoneNumber, $message);
    }

    // ----------------------------------------------------------------
    // 9.4  Exponential back-off retry (called from Job)
    // ----------------------------------------------------------------

    /**
     * Attempt to send and log; retry with exponential backoff on failure.
     */
    public function sendWithRetry(NotificationLog $log): void
    {
        $result = $this->sendMessage($log->phone_number, $log->message);

        if ($result['success']) {
            $log->markAsSent();
        } else {
            $retryCount = $log->retry_count + 1;
            $log->increment('retry_count');
            $log->update(['status' => NotificationLog::STATUS_RETRYING, 'error_code' => $result['error']]);

            if ($retryCount >= $this->maxRetries) {
                $log->markAsFailed($result['error'] ?? 'max_retries_exceeded');
                return;
            }

            // Exponential backoff: 30s, 60s, 120s ...
            $delaySeconds = 30 * (2 ** ($retryCount - 1));
            SendWhatsAppNotificationJob::dispatch($log)->delay(now()->addSeconds($delaySeconds));
        }
    }

    // ----------------------------------------------------------------
    // 9.5  Notification templates
    // ----------------------------------------------------------------

    /**
     * Build a localised (Indonesian) message for a given disaster.
     */
    public function buildDisasterMessage(Disaster $disaster): string
    {
        $typeLabel     = $this->disasterTypeLabel($disaster->type);
        $severityLabel = $this->severityLabel($disaster->severity);
        $tips          = $this->safetyTips($disaster->type);

        $extra = '';
        if ($disaster->type === 'earthquake' && isset($disaster->raw_data['magnitude'])) {
            $extra = "\n🔢 Magnitudo: M{$disaster->raw_data['magnitude']}";
        }

        return <<<MSG
🚨 *PERINGATAN BENCANA – ResQ*

📌 Jenis: {$typeLabel}
📍 Lokasi: {$disaster->location}
⚠️ Tingkat Keparahan: {$severityLabel}{$extra}
📝 Info: {$disaster->description}

🛡️ *Tips Keselamatan:*
{$tips}

🔗 Pantau peta bencana: https://resq.id/map
📞 Darurat Nasional: 119
MSG;
    }

    /**
     * Build opt-in confirmation message.
     */
    public function buildConfirmationMessage(string $disasterTypes): string
    {
        return <<<MSG
✅ *Selamat datang di ResQ Alert!*

Anda telah berhasil mendaftar untuk menerima notifikasi bencana via WhatsApp.

📌 Kategori bencana: {$disasterTypes}

Anda akan menerima peringatan dini saat terjadi bencana di Indonesia.
Untuk berhenti berlangganan, kunjungi: https://resq.id/notifications

Salam,
*Tim ResQ* 🇮🇩
MSG;
    }

    /**
     * Build opt-out confirmation message.
     */
    public function buildUnsubscribeMessage(): string
    {
        return <<<MSG
👋 *ResQ Alert – Berlangganan Dibatalkan*

Anda telah berhasil berhenti menerima notifikasi bencana ResQ.

Kami berharap Anda selalu aman. Untuk mulai berlangganan kembali kapan saja, kunjungi: https://resq.id/notifications

Salam,
*Tim ResQ* 🇮🇩
MSG;
    }

    // ----------------------------------------------------------------
    // 9.8  Opt-in / opt-out confirmation
    // ----------------------------------------------------------------

    /**
     * Dispatch a confirmation WhatsApp message after opt-in.
     */
    public function sendOptInConfirmation(NotificationPreference $preference): void
    {
        $types   = empty($preference->disaster_types)
            ? 'Semua jenis bencana'
            : implode(', ', array_map([$this, 'disasterTypeLabel'], $preference->disaster_types));

        $message = $this->buildConfirmationMessage($types);
        $log     = $this->createLog($preference->user_id, $preference->whatsapp_number, $message);
        SendWhatsAppNotificationJob::dispatch($log);
    }

    /**
     * Dispatch an unsubscribe confirmation WhatsApp message.
     */
    public function sendOptOutConfirmation(NotificationPreference $preference): void
    {
        $message = $this->buildUnsubscribeMessage();
        $log     = $this->createLog($preference->user_id, $preference->whatsapp_number, $message);
        SendWhatsAppNotificationJob::dispatch($log);
    }

    // ----------------------------------------------------------------
    // 9.9  Disaster-triggered notifications
    // ----------------------------------------------------------------

    /**
     * Trigger notifications to all eligible users for a new disaster.
     * Uses proximity filtering and rate-limiting.
     * Sends notification for ALL severity levels (low, medium, high, critical).
     */
    public function notifyForDisaster(Disaster $disaster): void
    {
        // Send notification for ALL severity levels

        $message = $this->buildDisasterMessage($disaster);

        // 9.10 Proximity-based filtering: prioritise users within 200km radius if coordinates available
        $preferences = NotificationPreference::active()->get()->filter(function ($pref) use ($disaster) {
            if (!$pref->isSubscribedTo($disaster->type)) {
                return false;
            }

            // If user has no location stored, include them anyway
            $user = $pref->user;
            if (!$user || !$user->preferences) {
                return true;
            }

            $userPrefs = is_array($user->preferences) ? $user->preferences : json_decode($user->preferences, true);
            if (!isset($userPrefs['latitude'], $userPrefs['longitude'])) {
                return true;
            }

            // Only notify users within 500km
            $distance = $this->haversineDistance(
                (float) $userPrefs['latitude'],
                (float) $userPrefs['longitude'],
                (float) $disaster->latitude,
                (float) $disaster->longitude
            );

            return $distance <= 500;
        });

        // 9.11 Rate limiting: chunk into batches dispatched with delay
        $batchSize  = $this->rateLimit; // 20 per minute
        $chunkIndex = 0;

        foreach ($preferences->chunk($batchSize) as $chunk) {
            $delayMinutes = $chunkIndex;

            foreach ($chunk as $pref) {
                // 9.11 Rate limit guard
                $rateLimitKey = 'whatsapp_notify_' . $pref->whatsapp_number;
                if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
                    Log::warning('Rate limit hit for number', ['phone' => $pref->whatsapp_number]);
                    continue;
                }
                RateLimiter::hit($rateLimitKey, 60);

                $log = $this->createLog($pref->user_id, $pref->whatsapp_number, $message);
                SendWhatsAppNotificationJob::dispatch($log)->delay(now()->addMinutes($delayMinutes));
            }

            $chunkIndex++;
        }
    }

    // ----------------------------------------------------------------
    // Helpers
    // ----------------------------------------------------------------

    /**
     * Create a NotificationLog entry.
     */
    public function createLog(?int $userId, string $phone, string $message): NotificationLog
    {
        return NotificationLog::create([
            'user_id'      => $userId,
            'phone_number' => $phone,
            'message'      => $message,
            'status'       => NotificationLog::STATUS_PENDING,
            'retry_count'  => 0,
        ]);
    }

    /**
     * Haversine formula – returns distance in km.
     */
    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371;
        $dLat        = deg2rad($lat2 - $lat1);
        $dLon        = deg2rad($lon2 - $lon1);
        $a           = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        return $earthRadius * 2 * asin(sqrt($a));
    }

    private function disasterTypeLabel(string $type): string
    {
        return match ($type) {
            'earthquake'    => 'Gempa Bumi',
            'flood'         => 'Banjir',
            'volcano'       => 'Letusan Gunung Berapi',
            'tsunami'       => 'Tsunami',
            'landslide'     => 'Tanah Longsor',
            'fire'          => 'Kebakaran',
            'hurricane'     => 'Angin Topan',
            default         => ucfirst($type),
        };
    }

    private function severityLabel(string $severity): string
    {
        return match ($severity) {
            'critical' => '🔴 Sangat Kritis',
            'high'     => '🟠 Tinggi',
            'medium'   => '🟡 Sedang',
            'low'      => '🟢 Rendah',
            default    => ucfirst($severity),
        };
    }

    private function safetyTips(string $type): string
    {
        return match ($type) {
            'earthquake' => "• Berlindung di bawah meja yang kokoh\n• Jauhi jendela dan lampu gantung\n• Tetap di dalam sampai guncangan berhenti",
            'flood'      => "• Segera evakuasi ke dataran tinggi\n• Hindari berjalan di air mengalir\n• Matikan listrik sebelum meninggalkan rumah",
            'volcano'    => "• Gunakan masker untuk abu vulkanik\n• Evakuasi menjauh dari lereng gunung\n• Perhatikan jalur evakuasi resmi",
            'tsunami'    => "• Segera lari ke area yang lebih tinggi\n• Hindari pantai meski air surut\n• Tunggu informasi resmi sebelum kembali",
            'landslide'  => "• Evakuasi segera dari area lereng\n• Perhatikan tanda retakan di tanah\n• Hubungi pihak berwenang",
            'fire'       => "• Tutup hidung dengan kain basah\n• Cari jalur keluar terdekat\n• Jangan gunakan lift",
            default      => "• Tetap tenang dan ikuti arahan petugas\n• Hubungi 119 untuk bantuan darurat\n• Perhatikan informasi resmi dari BNPB",
        };
    }
}
