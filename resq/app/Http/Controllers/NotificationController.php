<?php

namespace App\Http\Controllers;

use App\Models\NotificationLog;
use App\Models\NotificationPreference;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct(private readonly NotificationService $notificationService)
    {
    }

    // ----------------------------------------------------------------
    // 9.6  Notification preference management UI
    // ----------------------------------------------------------------

    /**
     * Show the notification preferences page.
     */
    public function index(): View
    {
        $user       = auth()->user();
        $preference = NotificationPreference::where('user_id', $user->id)->first();

        $disasterTypes = [
            'earthquake' => 'Gempa Bumi',
            'flood'      => 'Banjir',
            'volcano'    => 'Letusan Gunung Berapi',
            'tsunami'    => 'Tsunami',
            'landslide'  => 'Tanah Longsor',
            'fire'       => 'Kebakaran',
            'hurricane'  => 'Angin Topan',
        ];

        $recentLogs = NotificationLog::where('user_id', $user->id)
            ->latest()
            ->limit(10)
            ->get();

        return view('notifications.index', compact('preference', 'disasterTypes', 'recentLogs'));
    }

    /**
     * Store or update notification preferences (opt-in).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'whatsapp_number' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!NotificationPreference::validatePhoneNumber($value)) {
                    $fail('Nomor WhatsApp tidak valid. Gunakan format Indonesia, contoh: 08123456789');
                }
            }],
            'disaster_types'  => ['nullable', 'array'],
            'disaster_types.*' => ['string', 'in:earthquake,flood,volcano,tsunami,landslide,fire,hurricane'],
        ]);

        $user            = auth()->user();
        $normalizedPhone = NotificationPreference::normalizePhoneNumber($request->whatsapp_number);
        $isNew           = !NotificationPreference::where('user_id', $user->id)->exists();

        $preference = NotificationPreference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'whatsapp_number' => $normalizedPhone,
                'disaster_types'  => $request->disaster_types ?? [],
                'is_active'       => true,
            ]
        );

        // 9.8 Send confirmation only on new subscription
        if ($isNew) {
            $this->notificationService->sendOptInConfirmation($preference);
        }

        return redirect()->route('notifications.index')
            ->with('success', 'Preferensi notifikasi berhasil disimpan. Pesan konfirmasi telah dikirim ke WhatsApp Anda.');
    }

    /**
     * Deactivate notifications (opt-out).
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user       = auth()->user();
        $preference = NotificationPreference::where('user_id', $user->id)->firstOrFail();

        // 9.8 Send opt-out confirmation before deactivation
        $this->notificationService->sendOptOutConfirmation($preference);

        $preference->update(['is_active' => false]);

        return redirect()->route('notifications.index')
            ->with('success', 'Anda telah berhenti berlangganan notifikasi. Pesan konfirmasi telah dikirim.');
    }

    // ----------------------------------------------------------------
    // 9.12  Admin: notification logs view
    // ----------------------------------------------------------------

    /**
     * Show notification logs for admin.
     */
    public function adminLogs(Request $request): View
    {
        $query = NotificationLog::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $logs       = $query->paginate(25)->withQueryString();
        $statistics = NotificationLog::getStatistics();

        return view('notifications.admin-logs', compact('logs', 'statistics'));
    }

    /**
     * JSON endpoint: summary stats for dashboard widgets.
     */
    public function stats(): JsonResponse
    {
        $this->authorize('viewAny', NotificationLog::class);

        return response()->json([
            'success'    => true,
            'statistics' => NotificationLog::getStatistics(),
        ]);
    }
}
