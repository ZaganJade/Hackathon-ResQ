<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\NotificationPreference;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update the user's notification settings.
     */
    public function updateNotifications(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
            'disaster_types' => ['nullable', 'array'],
            'disaster_types.*' => ['string', 'in:earthquake,flood,tsunami,landslide,volcanic,fire,drought,other'],
            'min_alert_level' => ['nullable', 'string', 'in:low,moderate,high,critical'],
        ]);

        $user = $request->user();

        // Get or create notification preference
        $preference = $user->notificationPreference;

        if (!$preference) {
            $preference = new NotificationPreference();
            $preference->user_id = $user->id;
        }

        // Update fields
        $preference->whatsapp_number = $validated['whatsapp_number'] ?? $user->phone;
        $preference->is_active = $validated['is_active'] ?? false;
        $preference->disaster_types = $validated['disaster_types'] ?? [];
        $preference->min_alert_level = $validated['min_alert_level'] ?? 'moderate';

        $preference->save();

        return Redirect::route('profile.edit')->with('status', 'notification-settings-updated');
    }
}
