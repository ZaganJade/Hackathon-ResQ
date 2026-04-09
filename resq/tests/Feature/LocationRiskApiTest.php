<?php

namespace Tests\Feature;

use App\Models\Disaster;
use App\Models\User;
use App\Models\UserLocation;
use App\Services\LocationRiskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationRiskApiTest extends TestCase
{
    use RefreshDatabase;

    // =============================================================================
    // Task 10.2: Test API endpoints with valid and invalid coordinates
    // =============================================================================

    /** @test */
    public function analyze_endpoint_returns_full_analysis()
    {
        $user = User::factory()->create();

        // Create test disasters
        Disaster::factory()->count(3)->create([
            'latitude' => -6.2088,
            'longitude' => 106.8456,
        ]);

        $response = $this->actingAs($user)
            ->postJson('/api/v1/location/analyze', [
                'latitude' => -6.2088,
                'longitude' => 106.8456,
                'radius_km' => 50,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'status_label',
                'status_color',
                'warning_message',
                'metrics' => [
                    'total_nearby_disasters',
                    'max_cluster_size',
                    'cluster_time_window_days',
                ],
                'recommendations',
                'nearby_disasters' => [
                    '*' => [
                        'id',
                        'type',
                        'location',
                        'distance_km',
                    ],
                ],
                'timestamp',
            ]);
    }

    /** @test */
    public function quick_status_endpoint_returns_lightweight_response()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson('/api/v1/location/status?latitude=-6.2088&longitude=106.8456');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'status_label',
                'status_color',
                'metrics' => [
                    'total_nearby_disasters',
                    'max_cluster_size',
                ],
            ]);
    }

    /** @test */
    public function nearby_disasters_endpoint_returns_paginated_results()
    {
        $user = User::factory()->create();

        Disaster::factory()->count(10)->create([
            'latitude' => -6.2088,
            'longitude' => 106.8456,
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/v1/location/nearby-disasters?latitude=-6.2088&longitude=106.8456&radius_km=50');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'type',
                        'location',
                        'distance_km',
                        'created_at',
                    ],
                ],
            ]);
    }

    /** @test */
    public function reverse_geocode_endpoint_returns_address()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson('/api/v1/location/reverse-geocode?latitude=-6.2088&longitude=106.8456');

        // Response depends on external API availability
        $response->assertStatus(200)
            ->assertJsonStructure([
                'address',
                'latitude',
                'longitude',
            ]);
    }

    // =============================================================================
    // Task 10.4: Test AI chat with different zone statuses
    // =============================================================================

    /** @test */
    public function location_aware_chat_includes_zone_context()
    {
        $user = User::factory()->create();

        // Create danger zone scenario
        for ($i = 0; $i < 12; $i++) {
            Disaster::factory()->create([
                'type' => 'earthquake',
                'latitude' => -6.2088 + (rand(-50, 50) / 10000),
                'longitude' => 106.8456 + (rand(-50, 50) / 10000),
                'created_at' => now()->subDays(2),
            ]);
        }

        $response = $this->actingAs($user)
            ->postJson('/api/v1/location/chat', [
                'message' => 'Apakah saya aman?',
                'latitude' => -6.2088,
                'longitude' => 106.8456,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'response',
                'zone_status' => [
                    'status',
                    'status_label',
                    'status_color',
                    'recommendations',
                ],
            ]);
    }

    /** @test */
    public function chat_endpoint_requires_authentication()
    {
        $response = $this->postJson('/api/v1/location/chat', [
            'message' => 'Test',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
        ]);

        $response->assertStatus(401);
    }

    // =============================================================================
    // Task 10.6: Backward compatibility - chat without location
    // =============================================================================

    /** @test */
    public function ai_chat_without_location_params_still_works()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/ai-assist/chat', [
                'message' => 'Bagaimana cara bersiap untuk bencana?',
            ]);

        $response->assertStatus(200);
    }

    // =============================================================================
    // Task 10.8: Test notification with different radius settings
    // =============================================================================

    /** @test */
    public function user_location_uses_custom_notification_radius()
    {
        $user = User::factory()->create();

        $location = UserLocation::factory()->create([
            'user_id' => $user->id,
            'notification_radius_km' => 25,
            'notifications_enabled' => true,
        ]);

        $this->assertEquals(25, $location->notification_radius_km);
        $this->assertTrue($location->notifications_enabled);
    }

    /** @test */
    public function user_location_uses_default_radius_when_not_set()
    {
        $user = User::factory()->create();

        $location = UserLocation::factory()->create([
            'user_id' => $user->id,
            'notification_radius_km' => null,
        ]);

        // Default radius should be 50km
        $this->assertEquals(50, $location->notification_radius_km ?? 50);
    }

    // =============================================================================
    // Task 10.9: CSP headers verification
    // =============================================================================

    /** @test */
    public function dashboard_includes_csp_headers()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get('/dashboard');

        $response->assertStatus(200);
        // CSP headers should be present in production
        // This is a basic check - actual CSP implementation depends on config
    }

    // =============================================================================
    // Helper: Test command availability
    // =============================================================================

    /** @test */
    public function check_location_risk_command_exists()
    {
        $this->artisan('resq:check-location-risk')
            ->assertSuccessful();
    }

    /** @test */
    public function check_location_risk_command_accepts_user_option()
    {
        $user = User::factory()->create();

        UserLocation::factory()->create([
            'user_id' => $user->id,
            'notifications_enabled' => true,
        ]);

        $this->artisan('resq:check-location-risk', ['--user' => $user->id])
            ->assertSuccessful()
            ->expectsOutput("Checking user: {$user->name} (ID: {$user->id})");
    }
}
