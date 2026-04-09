<?php

namespace App\Http\Controllers;

use App\Models\UserLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserLocationController extends Controller
{
    /**
     * Display a listing of saved locations.
     */
    public function index()
    {
        $locations = auth()->user()->locations()
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('locations.index', compact('locations'));
    }

    /**
     * Show the form for creating a new location.
     */
    public function create()
    {
        return view('locations.create');
    }

    /**
     * Store a newly created location.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:500',
            'is_default' => 'boolean',
            'notifications_enabled' => 'boolean',
            'notification_radius_km' => 'required|integer|min:10|max:500',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['is_default'] = $request->boolean('is_default', false);
        $validated['notifications_enabled'] = $request->boolean('notifications_enabled', true);

        // If this is the first location, make it default
        if (!auth()->user()->locations()->exists()) {
            $validated['is_default'] = true;
        }

        UserLocation::create($validated);

        return redirect()->route('locations.index')
            ->with('success', 'Lokasi berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified location.
     */
    public function edit(UserLocation $location)
    {
        $this->authorize('update', $location);

        return view('locations.edit', compact('location'));
    }

    /**
     * Update the specified location.
     */
    public function update(Request $request, UserLocation $location)
    {
        $this->authorize('update', $location);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'address' => 'nullable|string|max:500',
            'is_default' => 'boolean',
            'notifications_enabled' => 'boolean',
            'notification_radius_km' => 'required|integer|min:10|max:500',
        ]);

        $validated['is_default'] = $request->boolean('is_default', false);
        $validated['notifications_enabled'] = $request->boolean('notifications_enabled', true);

        $location->update($validated);

        return redirect()->route('locations.index')
            ->with('success', 'Lokasi berhasil diperbarui!');
    }

    /**
     * Remove the specified location.
     */
    public function destroy(UserLocation $location)
    {
        $this->authorize('delete', $location);

        $location->delete();

        // If deleted location was default, set another as default
        if ($location->was_default && auth()->user()->locations()->exists()) {
            auth()->user()->locations()->first()->update(['is_default' => true]);
        }

        return redirect()->route('locations.index')
            ->with('success', 'Lokasi berhasil dihapus!');
    }

    /**
     * Set location as default.
     */
    public function setDefault(UserLocation $location)
    {
        $this->authorize('update', $location);

        auth()->user()->locations()->update(['is_default' => false]);
        $location->update(['is_default' => true]);

        return redirect()->route('locations.index')
            ->with('success', 'Lokasi default berhasil diubah!');
    }

    /**
     * Get current user's default location (for AI chat fallback).
     */
    public function getCurrentLocation()
    {
        $location = auth()->user()->locations()
            ->where('is_default', true)
            ->first();

        if (!$location) {
            // Get any location if no default
            $location = auth()->user()->locations()->first();
        }

        if ($location) {
            return response()->json([
                'success' => true,
                'latitude' => $location->latitude,
                'longitude' => $location->longitude,
                'name' => $location->name,
                'address' => $location->address,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No saved location found',
        ], 404);
    }

    /**
     * Reverse geocode address from coordinates.
     */
    public function reverseGeocode(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        try {
            $response = Http::get('https://nominatim.openstreetmap.org/reverse', [
                'lat' => $request->latitude,
                'lon' => $request->longitude,
                'format' => 'json',
                'accept-language' => 'id',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'success' => true,
                    'address' => $data['display_name'] ?? null,
                    'place' => $data['name'] ?? null,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan alamat',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
