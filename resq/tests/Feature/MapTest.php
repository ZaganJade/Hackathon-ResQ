<?php

namespace Tests\Feature;

use App\Models\Disaster;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MapTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        // Create test disasters
        $this->createTestDisasters();
    }

    private function createTestDisasters(): void
    {
        // Earthquake in Jakarta
        Disaster::create([
            'type' => 'earthquake',
            'location' => 'Jakarta',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'severity' => 'high',
            'status' => 'active',
            'description' => 'Gempa bumi magnitude 6.5 di Jakarta',
            'source' => 'test',
            'source_id' => 'test_001',
            'raw_data' => ['magnitude' => 6.5],
        ]);

        // Flood in Bandung
        Disaster::create([
            'type' => 'flood',
            'location' => 'Bandung',
            'latitude' => -6.9175,
            'longitude' => 107.6191,
            'severity' => 'medium',
            'status' => 'active',
            'description' => 'Banjir di wilayah Bandung',
            'source' => 'test',
            'source_id' => 'test_002',
            'raw_data' => [],
        ]);

        // Landslide in Yogyakarta
        Disaster::create([
            'type' => 'landslide',
            'location' => 'Yogyakarta',
            'latitude' => -7.7971,
            'longitude' => 110.3688,
            'severity' => 'low',
            'status' => 'active',
            'description' => 'Longsor di daerah hutan Yogyakarta',
            'source' => 'test',
            'source_id' => 'test_003',
            'raw_data' => [],
        ]);

        // Tsunami in Aceh (resolved)
        Disaster::create([
            'type' => 'tsunami',
            'location' => 'Aceh',
            'latitude' => 4.6951,
            'longitude' => 96.7494,
            'severity' => 'critical',
            'status' => 'resolved',
            'description' => 'Peringatan tsunami',
            'source' => 'test',
            'source_id' => 'test_004',
            'raw_data' => [],
            'resolved_at' => now(),
        ]);
    }

    public function test_authenticated_user_can_access_map_page(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('map.index'));

        $response->assertStatus(200)
            ->assertViewIs('map.index')
            ->assertViewHas('mapConfig')
            ->assertViewHas('disasterTypes')
            ->assertViewHas('googleMapsApiKey')
            ->assertSee('Peta Bencana');
    }

    public function test_guest_cannot_access_map_page(): void
    {
        $response = $this->get(route('map.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_api_returns_disasters_in_geojson_format(): void
    {
        $response = $this->getJson(route('api.disasters.index'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'type',
                'features' => [
                    '*' => [
                        'type',
                        'geometry' => [
                            'type',
                            'coordinates',
                        ],
                        'properties' => [
                            'id',
                            'type',
                            'location',
                            'severity',
                            'status',
                            'description',
                            'color',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ],
                'meta' => [
                    'total',
                    'filters',
                ],
            ])
            ->assertJson([
                'type' => 'FeatureCollection',
            ]);

        $this->assertCount(4, $response->json('features'));
    }

    public function test_api_filters_disasters_by_type(): void
    {
        $response = $this->getJson(route('api.disasters.index', [
            'types' => ['earthquake'],
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(1, 'features')
            ->assertJsonPath('features.0.properties.type', 'earthquake');
    }

    public function test_api_filters_disasters_by_multiple_types(): void
    {
        $response = $this->getJson(route('api.disasters.index', [
            'types' => ['earthquake', 'flood'],
        ]));

        $response->assertStatus(200)
            ->assertJsonCount(2, 'features');

        $types = collect($response->json('features'))->pluck('properties.type');
        $this->assertContains('earthquake', $types->toArray());
        $this->assertContains('flood', $types->toArray());
    }

    public function test_api_filters_disasters_by_severity(): void
    {
        $response = $this->getJson(route('api.disasters.index', [
            'severity' => ['high'],
        ]));

        $response->assertStatus(200);

        foreach ($response->json('features') as $feature) {
            $this->assertEquals('high', $feature['properties']['severity']);
        }
    }

    public function test_api_filters_disasters_by_multiple_severities(): void
    {
        $response = $this->getJson(route('api.disasters.index', [
            'severity' => ['high', 'critical'],
        ]));

        $response->assertStatus(200);

        foreach ($response->json('features') as $feature) {
            $this->assertContains($feature['properties']['severity'], ['high', 'critical']);
        }
    }

    public function test_api_filters_disasters_by_date_range(): void
    {
        $today = now()->format('Y-m-d');
        $yesterday = now()->subDay()->format('Y-m-d');

        $response = $this->getJson(route('api.disasters.index', [
            'date_from' => $yesterday,
            'date_to' => $today,
        ]));

        $response->assertStatus(200)
            ->assertJsonPath('meta.filters.date_from', $yesterday)
            ->assertJsonPath('meta.filters.date_to', $today);
    }

    public function test_api_returns_disasters_within_radius(): void
    {
        // Search for disasters near Jakarta (within 100km)
        $response = $this->getJson(route('api.disasters.index', [
            'lat' => -6.2088,
            'lng' => 106.8456,
            'radius' => 100,
        ]));

        $response->assertStatus(200);

        // Jakarta earthquake should be included (0km from center)
        $locations = collect($response->json('features'))->pluck('properties.location');
        $this->assertContains('Jakarta', $locations->toArray());
    }

    public function test_api_validates_radius_parameter(): void
    {
        $response = $this->getJson(route('api.disasters.index', [
            'lat' => -6.2088,
            'lng' => 106.8456,
            'radius' => 501, // Max is 500
        ]));

        $response->assertStatus(422);
    }

    public function test_api_validates_coordinate_parameters(): void
    {
        $response = $this->getJson(route('api.disasters.index', [
            'lat' => 100, // Invalid: must be -90 to 90
            'lng' => 106.8456,
            'radius' => 50,
        ]));

        $response->assertStatus(422);
    }

    public function test_geocode_endpoint_returns_coordinates(): void
    {
        // Mock Nominatim (OpenStreetMap) geocoding - free, no API key needed
        Http::fake([
            'https://nominatim.openstreetmap.org/search*' => Http::response([
                [
                    'lat' => '-6.2088',
                    'lon' => '106.8456',
                    'display_name' => 'Jakarta, Indonesia',
                ],
            ], 200),
        ]);

        $response = $this->getJson(route('api.geocode', [
            'location' => 'Jakarta',
        ]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'location' => [
                    'lat',
                    'lng',
                    'formatted_address',
                    'source',
                ],
                'nearby_disasters' => [
                    'count',
                    'radius_km',
                ],
            ])
            ->assertJsonPath('location.lat', -6.2088)
            ->assertJsonPath('location.lng', 106.8456)
            ->assertJsonPath('location.formatted_address', 'Jakarta, Indonesia')
            ->assertJsonPath('location.source', 'nominatim');
    }

    public function test_geocode_returns_404_for_invalid_location(): void
    {
        // Mock Nominatim returning empty results
        Http::fake([
            'https://nominatim.openstreetmap.org/search*' => Http::response([], 200),
        ]);

        $response = $this->getJson(route('api.geocode', [
            'location' => 'InvalidLocationXYZ123',
        ]));

        $response->assertStatus(404)
            ->assertJson([
                'error' => 'Location not found',
            ]);
    }

    public function test_geocode_validates_location_parameter(): void
    {
        $response = $this->getJson(route('api.geocode'));

        $response->assertStatus(422);
    }

    public function test_stats_endpoint_returns_aggregated_data(): void
    {
        $response = $this->getJson(route('api.disasters.stats'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'by_type',
                'by_severity',
                'total',
            ])
            ->assertJsonPath('total', 4);

        // Check severity aggregation
        $bySeverity = $response->json('by_severity');
        $this->assertArrayHasKey('high', $bySeverity);
        $this->assertArrayHasKey('medium', $bySeverity);
        $this->assertArrayHasKey('low', $bySeverity);
        $this->assertArrayHasKey('critical', $bySeverity);

        // Check type aggregation
        $byType = $response->json('by_type');
        $this->assertArrayHasKey('earthquake', $byType);
        $this->assertArrayHasKey('flood', $byType);
        $this->assertArrayHasKey('landslide', $byType);
        $this->assertArrayHasKey('tsunami', $byType);
    }

    public function test_show_endpoint_returns_single_disaster(): void
    {
        $disaster = Disaster::first();

        $response = $this->getJson(route('api.disasters.show', ['disaster' => $disaster->id]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'type',
                'location',
                'latitude',
                'longitude',
                'severity',
                'status',
                'description',
                'source',
                'source_id',
                'raw_data',
                'color',
                'created_at',
                'updated_at',
                'resolved_at',
            ])
            ->assertJson([
                'id' => $disaster->id,
                'type' => $disaster->type,
            ]);
    }

    public function test_show_endpoint_returns_404_for_invalid_disaster(): void
    {
        $response = $this->getJson(route('api.disasters.show', ['disaster' => 99999]));

        $response->assertStatus(404);
    }

    public function test_disaster_has_correct_severity_colors(): void
    {
        $response = $this->getJson(route('api.disasters.index'));

        $response->assertStatus(200);

        $features = $response->json('features');

        foreach ($features as $feature) {
            $severity = $feature['properties']['severity'];
            $color = $feature['properties']['color'];

            match ($severity) {
                'critical', 'high' => $this->assertEquals('#f43f5e', $color), // Rose-500 (Tailwind v4)
                'medium' => $this->assertEquals('#f59e0b', $color), // Amber-500
                'low' => $this->assertEquals('#059669', $color), // Emerald-600
                default => $this->assertEquals('#6B7280', $color), // Gray-500
            };
        }
    }

    public function test_api_handles_combined_filters(): void
    {
        $response = $this->getJson(route('api.disasters.index', [
            'types' => ['earthquake', 'flood', 'landslide'],
            'severity' => ['high', 'critical'],
            'date_from' => now()->subDays(7)->format('Y-m-d'),
            'date_to' => now()->format('Y-m-d'),
        ]));

        $response->assertStatus(200)
            ->assertJsonPath('meta.filters.types', ['earthquake', 'flood', 'landslide'])
            ->assertJsonPath('meta.filters.severity', ['high', 'critical'])
            ->assertJsonPath('meta.filters.date_from', now()->subDays(7)->format('Y-m-d'));
    }

    public function test_api_geojson_coordinates_are_valid(): void
    {
        $response = $this->getJson(route('api.disasters.index'));

        $response->assertStatus(200);

        foreach ($response->json('features') as $feature) {
            $coords = $feature['geometry']['coordinates'];

            // Longitude first in GeoJSON format
            $this->assertIsFloat($coords[0]);
            $this->assertIsFloat($coords[1]);

            // Validate ranges
            $this->assertGreaterThanOrEqual(-180, $coords[0]);
            $this->assertLessThanOrEqual(180, $coords[0]);
            $this->assertGreaterThanOrEqual(-90, $coords[1]);
            $this->assertLessThanOrEqual(90, $coords[1]);
        }
    }

    public function test_map_page_contains_required_javascript(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('map.index'));

        $response->assertStatus(200)
            ->assertSee('leaflet')
            ->assertSee('L.map')
            ->assertSee('loadDisasters')
            ->assertSee('markerCluster')
            ->assertSee('autoRefreshInterval')
            ->assertSee('bindPopup');
    }

    public function test_map_page_includes_filter_ui_elements(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('map.index'));

        $response->assertStatus(200)
            ->assertSee('location-search')
            ->assertSee('search-btn')
            ->assertSee('radius-select')
            ->assertSee('disaster-type-filters')
            ->assertSee('severity')
            ->assertSee('auto-refresh')
            ->assertSee('reset-filters');
    }
}
