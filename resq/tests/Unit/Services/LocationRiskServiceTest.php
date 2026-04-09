<?php

namespace Tests\Unit\Services;

use App\Models\Disaster;
use App\Models\User;
use App\Models\UserLocation;
use App\Services\LocationRiskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocationRiskServiceTest extends TestCase
{
    use RefreshDatabase;

    private LocationRiskService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(LocationRiskService::class);
    }

    // =============================================================================
    // Task 10.1: Test with various cluster scenarios
    // =============================================================================

    /** @test */
    public function it_returns_danger_status_for_high_cluster_count()
    {
        // Create 11 disasters within 50km (threshold: 10+)
        $centerLat = -6.2088;
        $centerLng = 106.8456;

        for ($i = 0; $i < 11; $i++) {
            Disaster::factory()->create([
                'type' => 'earthquake',
                'latitude' => $centerLat + (rand(-100, 100) / 10000),
                'longitude' => $centerLng + (rand(-100, 100) / 10000),
                'created_at' => now()->subDays(5),
            ]);
        }

        $result = $this->service->analyzeZoneStatus($centerLat, $centerLng);

        $this->assertEquals(LocationRiskService::STATUS_DANGER, $result['status']);
        $this->assertEquals('Zona Berbahaya', $result['status_label']);
    }

    /** @test */
    public function it_returns_warning_status_for_medium_cluster_size()
    {
        // Create 6 disasters with max cluster of 3 (threshold: 5-9 disasters, cluster <=5)
        $centerLat = -6.2088;
        $centerLng = 106.8456;

        for ($i = 0; $i < 6; $i++) {
            Disaster::factory()->create([
                'type' => 'flood',
                'latitude' => $centerLat + (rand(-500, 500) / 10000),
                'longitude' => $centerLng + (rand(-500, 500) / 10000),
                'created_at' => now()->subDays(10),
            ]);
        }

        $result = $this->service->analyzeZoneStatus($centerLat, $centerLng);

        $this->assertEquals(LocationRiskService::STATUS_WARNING, $result['status']);
        $this->assertEquals('Zona Waspada', $result['status_label']);
    }

    /** @test */
    public function it_returns_safe_status_for_low_disaster_count()
    {
        // Create 4 disasters (threshold: <5 is safe)
        $centerLat = -6.2088;
        $centerLng = 106.8456;

        for ($i = 0; $i < 4; $i++) {
            Disaster::factory()->create([
                'type' => 'fire',
                'latitude' => $centerLat + (rand(-1000, 1000) / 10000),
                'longitude' => $centerLng + (rand(-1000, 1000) / 10000),
                'created_at' => now()->subDays(15),
            ]);
        }

        $result = $this->service->analyzeZoneStatus($centerLat, $centerLng);

        $this->assertEquals(LocationRiskService::STATUS_SAFE, $result['status']);
        $this->assertEquals('Zona Aman', $result['status_label']);
    }

    /** @test */
    public function it_ignores_disasters_outside_30_day_window()
    {
        // Create old disasters (outside 30 days)
        $centerLat = -6.2088;
        $centerLng = 106.8456;

        for ($i = 0; $i < 15; $i++) {
            Disaster::factory()->create([
                'type' => 'earthquake',
                'latitude' => $centerLat + (rand(-100, 100) / 10000),
                'longitude' => $centerLng + (rand(-100, 100) / 10000),
                'created_at' => now()->subDays(35), // Outside 30-day window
            ]);
        }

        $result = $this->service->analyzeZoneStatus($centerLat, $centerLng);

        // Should be safe since disasters are too old
        $this->assertEquals(LocationRiskService::STATUS_SAFE, $result['status']);
    }

    // =============================================================================
    // Task 10.2: Test API endpoints with valid and invalid coordinates
    // =============================================================================

    /** @test */
    public function api_rejects_invalid_latitude()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson('/api/v1/location/status?latitude=91&longitude=106.8456');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['latitude']);
    }

    /** @test */
    public function api_rejects_invalid_longitude()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson('/api/v1/location/status?latitude=-6.2088&longitude=181');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['longitude']);
    }

    /** @test */
    public function api_accepts_valid_coordinates()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->getJson('/api/v1/location/status?latitude=-6.2088&longitude=106.8456');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'status_label',
                'status_color',
                'metrics',
                'recommendations',
                'nearby_disasters',
            ]);
    }

    // =============================================================================
    // Task 10.7: Test multiple saved locations per user
    // =============================================================================

    /** @test */
    public function user_can_have_multiple_saved_locations()
    {
        $user = User::factory()->create();

        $location1 = UserLocation::factory()->create([
            'user_id' => $user->id,
            'name' => 'Rumah',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'is_default' => true,
        ]);

        $location2 = UserLocation::factory()->create([
            'user_id' => $user->id,
            'name' => 'Kantor',
            'latitude' => -6.9147,
            'longitude' => 107.6098,
            'is_default' => false,
        ]);

        $this->assertCount(2, $user->locations);
        $this->assertTrue($user->defaultLocation()->is($location1));
    }

    // =============================================================================
    // Haversine distance calculation test
    // =============================================================================

    /** @test */
    public function it_calculates_distance_correctly_using_haversine()
    {
        // Distance between Jakarta (-6.2088, 106.8456) and Bandung (-6.9147, 107.6098)
        // Should be approximately 120km
        $distance = $this->service->calculateDistance(-6.2088, 106.8456, -6.9147, 107.6098);

        $this->assertGreaterThan(110, $distance);
        $this->assertLessThan(130, $distance);
    }

    // =============================================================================
    // Time cluster calculation test
    // =============================================================================

    /** @test */
    public function it_detects_time_clusters_correctly()
    {
        $centerLat = -6.2088;
        $centerLng = 106.8456;

        // Create 6 disasters within 3 days (cluster threshold: 5 in 7 days)
        for ($i = 0; $i < 6; $i++) {
            Disaster::factory()->create([
                'type' => 'landslide',
                'latitude' => $centerLat + (rand(-100, 100) / 10000),
                'longitude' => $centerLng + (rand(-100, 100) / 10000),
                'created_at' => now()->subDays(rand(0, 3)),
            ]);
        }

        $result = $this->service->analyzeZoneStatus($centerLat, $centerLng);

        $this->assertGreaterThanOrEqual(6, $result['metrics']['total_nearby_disasters']);
    }
}
