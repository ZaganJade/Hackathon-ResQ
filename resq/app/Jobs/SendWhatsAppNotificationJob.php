<?php

namespace App\Jobs;

use App\Models\NotificationLog;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWhatsAppNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     * We handle retries manually inside NotificationService to control backoff.
     */
    public int $tries = 1;

    /**
     * Timeout in seconds.
     * Must be longer than WhatsApp API timeout (60s) to allow proper error handling.
     */
    public int $timeout = 75;

    public function __construct(public readonly NotificationLog $notificationLog)
    {
    }

    public function handle(NotificationService $notificationService): void
    {
        // Guard: skip if already terminal state
        if (in_array($this->notificationLog->status, [
            NotificationLog::STATUS_SENT,
            NotificationLog::STATUS_DELIVERED,
            NotificationLog::STATUS_FAILED,
        ])) {
            return;
        }

        $notificationService->sendWithRetry($this->notificationLog);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('SendWhatsAppNotificationJob failed', [
            'log_id' => $this->notificationLog->id,
            'error'  => $exception->getMessage(),
        ]);

        $this->notificationLog->markAsFailed('job_exception: ' . $exception->getMessage());
    }
}
