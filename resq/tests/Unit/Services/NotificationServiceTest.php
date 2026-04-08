<?php

namespace Tests\Unit\Services;

use App\Jobs\SendWhatsAppNotificationJob;
use App\Models\Disaster;
use App\Models\NotificationLog;
use App\Models\NotificationPreference;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class NotificationServiceTest extends TestCase
{
    use RefreshDatabase;

    private NotificationService $service;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(NotificationService::class);

        $this->user = User::factory()->create([
            'name'  => 'Test User',
            'email' => 'test@resq.id',
        ]);
    }

    // ----------------------------------------------------------------
    // 9.1 / 9.2  sendMessage (HTTP client)
    // ----------------------------------------------------------------

    public function test_send_message_returns_success_on_200(): void
    {
        Http::fake([
            '*' => Http::response(['id' => 'msg_abc123'], 200),
        ]);

        $result = $this->service->sendMessage('+6281234567890', 'Test pesan bencana');

        $this->assertTrue($result['success']);
        $this->assertEquals('msg_abc123', $result['message_id']);
        $this->assertNull($result['error']);
    }

    public function test_send_message_returns_failure_on_non_2xx(): void
    {
        Http::fake([
            '*' => Http::response(['error' => 'unauthorized'], 401),
        ]);

        $result = $this->service->sendMessage('+6281234567890', 'Test pesan');

        $this->assertFalse($result['success']);
        $this->assertNull($result['message_id']);
        $this->assertNotNull($result['error']);
    }

    public function test_send_message_handles_network_exception(): void
    {
        Http::fake(function () {
            throw new \Exception('Connection refused');
        });

        $result = $this->service->sendMessage('+6281234567890', 'Test pesan');

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Connection refused', $result['error']);
    }

    // ----------------------------------------------------------------
    // 9.3 / 9.4  Queue job + retry logic
    // ----------------------------------------------------------------

    public function test_send_with_retry_marks_log_sent_on_success(): void
    {
        Http::fake([
            '*' => Http::response(['id' => 'msg_ok'], 200),
        ]);

        $log = NotificationLog::create([
            'user_id'      => $this->user->id,
            'phone_number' => '+6281234567890',
            'message'      => 'Alert: bencana terjadi',
            'status'       => NotificationLog::STATUS_PENDING,
            'retry_count'  => 0,
        ]);

        $this->service->sendWithRetry($log);

        $this->assertEquals(NotificationLog::STATUS_SENT, $log->fresh()->status);
        $this->assertNotNull($log->fresh()->sent_at);
    }

    public function test_send_with_retry_queues_retry_on_failure(): void
    {
        Queue::fake();
        Http::fake([
            '*' => Http::response(['error' => 'server error'], 500),
        ]);

        $log = NotificationLog::create([
            'user_id'      => $this->user->id,
            'phone_number' => '+6281234567890',
            'message'      => 'Alert bencana',
            'status'       => NotificationLog::STATUS_PENDING,
            'retry_count'  => 0,
        ]);

        $this->service->sendWithRetry($log);

        Queue::assertPushed(SendWhatsAppNotificationJob::class);
        $this->assertEquals(NotificationLog::STATUS_RETRYING, $log->fresh()->status);
    }

    public function test_send_with_retry_marks_failed_after_max_retries(): void
    {
        Http::fake([
            '*' => Http::response(['error' => 'server error'], 500),
        ]);

        $log = NotificationLog::create([
            'user_id'      => $this->user->id,
            'phone_number' => '+6281234567890',
            'message'      => 'Alert bencana',
            'status'       => NotificationLog::STATUS_RETRYING,
            'retry_count'  => 2, // already at 2, next attempt = 3 = max
        ]);

        $this->service->sendWithRetry($log);

        $this->assertEquals(NotificationLog::STATUS_FAILED, $log->fresh()->status);
    }

    // ----------------------------------------------------------------
    // 9.5  Templates
    // ----------------------------------------------------------------

    public function test_build_disaster_message_contains_required_fields(): void
    {
        $disaster = Disaster::factory()->create([
            'type'        => 'earthquake',
            'location'    => 'Yogyakarta, DIY',
            'severity'    => 'high',
            'description' => 'Gempa bumi M6.5 mengguncang wilayah Yogyakarta',
            'raw_data'    => ['magnitude' => 6.5],
        ]);

        $message = $this->service->buildDisasterMessage($disaster);

        $this->assertStringContainsString('Gempa Bumi', $message);
        $this->assertStringContainsString('Yogyakarta', $message);
        $this->assertStringContainsString('6.5', $message);
        $this->assertStringContainsString('ResQ', $message);
    }

    public function test_build_confirmation_message_in_indonesian(): void
    {
        $message = $this->service->buildConfirmationMessage('Gempa Bumi, Banjir');

        $this->assertStringContainsString('berhasil mendaftar', $message);
        $this->assertStringContainsString('Gempa Bumi', $message);
    }

    public function test_build_unsubscribe_message_in_indonesian(): void
    {
        $message = $this->service->buildUnsubscribeMessage();

        $this->assertStringContainsString('berhenti menerima', $message);
    }

    // ----------------------------------------------------------------
    // 9.7  Phone number validation (via Model – tested here for coverage)
    // ----------------------------------------------------------------

    public function test_valid_indonesian_numbers_pass_validation(): void
    {
        $valid = ['08123456789', '+6281234567890', '6281234567890', '081234567890'];
        foreach ($valid as $number) {
            $this->assertTrue(NotificationPreference::validatePhoneNumber($number), "Expected {$number} to be valid");
        }
    }

    public function test_invalid_numbers_fail_validation(): void
    {
        $invalid = ['1234567', '07123456789', '00123456789', 'notanumber'];
        foreach ($invalid as $number) {
            $this->assertFalse(NotificationPreference::validatePhoneNumber($number), "Expected {$number} to be invalid");
        }
    }

    public function test_normalize_phone_converts_local_to_international(): void
    {
        $this->assertEquals('+6281234567890', NotificationPreference::normalizePhoneNumber('081234567890'));
        $this->assertEquals('+6281234567890', NotificationPreference::normalizePhoneNumber('6281234567890'));
    }

    // ----------------------------------------------------------------
    // 9.8  Opt-in / opt-out confirmation
    // ----------------------------------------------------------------

    public function test_send_opt_in_confirmation_dispatches_job(): void
    {
        Queue::fake();

        $preference = NotificationPreference::create([
            'user_id'         => $this->user->id,
            'whatsapp_number' => '+6281234567890',
            'disaster_types'  => ['earthquake', 'flood'],
            'is_active'       => true,
        ]);

        $this->service->sendOptInConfirmation($preference);

        Queue::assertPushed(SendWhatsAppNotificationJob::class);
    }

    public function test_send_opt_out_confirmation_dispatches_job(): void
    {
        Queue::fake();

        $preference = NotificationPreference::create([
            'user_id'         => $this->user->id,
            'whatsapp_number' => '+6281234567890',
            'disaster_types'  => [],
            'is_active'       => false,
        ]);

        $this->service->sendOptOutConfirmation($preference);

        Queue::assertPushed(SendWhatsAppNotificationJob::class);
    }

    // ----------------------------------------------------------------
    // 9.9 / 9.10  Disaster triggered notifications + proximity filtering
    // ----------------------------------------------------------------

    public function test_notify_for_disaster_dispatches_jobs_for_active_subscribers(): void
    {
        Queue::fake();

        $disaster = Disaster::factory()->create([
            'type'      => 'earthquake',
            'severity'  => 'high',
            'latitude'  => -7.801194,
            'longitude' => 110.364917,
        ]);

        NotificationPreference::create([
            'user_id'         => $this->user->id,
            'whatsapp_number' => '+6281234567890',
            'disaster_types'  => ['earthquake'],
            'is_active'       => true,
        ]);

        $this->service->notifyForDisaster($disaster);

        Queue::assertPushed(SendWhatsAppNotificationJob::class);
    }

    public function test_notify_for_disaster_skips_low_severity(): void
    {
        Queue::fake();

        $disaster = Disaster::factory()->create([
            'type'     => 'earthquake',
            'severity' => 'low',
        ]);

        NotificationPreference::create([
            'user_id'         => $this->user->id,
            'whatsapp_number' => '+6281234567890',
            'disaster_types'  => [],
            'is_active'       => true,
        ]);

        $this->service->notifyForDisaster($disaster);

        Queue::assertNotPushed(SendWhatsAppNotificationJob::class);
    }

    public function test_notify_for_disaster_skips_unsubscribed_types(): void
    {
        Queue::fake();

        $disaster = Disaster::factory()->create([
            'type'     => 'flood',
            'severity' => 'high',
        ]);

        NotificationPreference::create([
            'user_id'         => $this->user->id,
            'whatsapp_number' => '+6281234567890',
            'disaster_types'  => ['earthquake'], // not subscribed to flood
            'is_active'       => true,
        ]);

        $this->service->notifyForDisaster($disaster);

        Queue::assertNotPushed(SendWhatsAppNotificationJob::class);
    }
}
