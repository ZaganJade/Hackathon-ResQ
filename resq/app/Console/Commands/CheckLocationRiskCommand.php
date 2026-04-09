<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserLocation;
use App\Services\LocationRiskService;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class CheckLocationRiskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resq:check-location-risk
                            {--user= : Check specific user ID}
                            {--notify : Send notifications to users in danger zones}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check disaster risk for user locations and optionally send notifications';

    private LocationRiskService $riskService;
    private NotificationService $notificationService;

    public function __construct(LocationRiskService $riskService, NotificationService $notificationService)
    {
        parent::__construct();
        $this->riskService = $riskService;
        $this->notificationService = $notificationService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking location risks...');

        // Get users to check
        if ($this->option('user')) {
            $users = User::where('id', $this->option('user'))->get();
        } else {
            $users = User::has('locations')->get();
        }

        $dangerCount = 0;
        $warningCount = 0;
        $safeCount = 0;

        foreach ($users as $user) {
            $this->info("Checking user: {$user->name} (ID: {$user->id})");

            foreach ($user->locations()->notificationsEnabled()->get() as $location) {
                $analysis = $this->riskService->analyzeZoneStatus(
                    (float) $location->latitude,
                    (float) $location->longitude,
                    $location->notification_radius_km
                );

                $status = $analysis['status'];

                $this->line("  Location: {$location->name} - Status: {$analysis['status_label']}");
                $this->line("  Total disasters: {$analysis['metrics']['total_nearby_disasters']}");
                $this->line("  Max cluster: {$analysis['metrics']['max_cluster_size']}");

                // Count statuses
                match ($status) {
                    LocationRiskService::STATUS_DANGER => $dangerCount++,
                    LocationRiskService::STATUS_WARNING => $warningCount++,
                    default => $safeCount++,
                };

                // Send notification if requested and in danger/warning zone
                if ($this->option('notify') && in_array($status, [LocationRiskService::STATUS_DANGER, LocationRiskService::STATUS_WARNING])) {
                    $this->sendRiskNotification($user, $location, $analysis);
                }
            }
        }

        $this->newLine();
        $this->info("Summary:");
        $this->info("  Danger zones: {$dangerCount}");
        $this->info("  Warning zones: {$warningCount}");
        $this->info("  Safe zones: {$safeCount}");

        return Command::SUCCESS;
    }

    /**
     * Send risk notification to user
     */
    private function sendRiskNotification(User $user, UserLocation $location, array $analysis): void
    {
        $status = $analysis['status'];
        $title = match ($status) {
            LocationRiskService::STATUS_DANGER => '⚠️ PERINGATAN: Zona Berbahaya Terdeteksi!',
            LocationRiskService::STATUS_WARNING => '⚡ Peringatan: Zona Waspada',
            default => null,
        };

        if (!$title) return;

        $message = "Lokasi {$location->name}: {$analysis['warning_message']}\n\n";
        $message .= "Rekomendasi:\n";
        foreach (array_slice($analysis['recommendations'], 0, 3) as $rec) {
            $message .= "- {$rec}\n";
        }

        // Send WhatsApp notification if user has phone
        if ($user->phone) {
            try {
                $this->notificationService->sendWhatsApp($user->phone, $message);
                $this->info("  WhatsApp notification sent to {$user->phone}");
            } catch (\Throwable $e) {
                $this->error("  Failed to send WhatsApp: {$e->getMessage()}");
            }
        }

        // Log the notification
        \App\Models\NotificationLog::create([
            'user_id' => $user->id,
            'channel' => 'whatsapp',
            'type' => $status === LocationRiskService::STATUS_DANGER ? 'danger_alert' : 'warning_alert',
            'title' => $title,
            'message' => $message,
            'status' => 'pending',
        ]);
    }
}
