<?php

namespace Tests\Unit\Services;

use App\Services\GeoService;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;

class GeoServiceTest extends TestCase
{
    private GeoService $geoService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->geoService = new GeoService();
    }

    public function test_calculate_distance_returns_correct_kilometers(): void
    {
        // Jakarta to Bandung (approximately 120 km)
        $jakartaLat = -6.2088;
        $jakartaLng = 106.8456;
        $bandungLat = -6.9175;
        $bandungLng = 107.6191;

        $distance = $this->geoService->calculateDistance($jakartaLat, $jakartaLng, $bandungLat, $bandungLng);

        // Should be approximately 120-125 km
        $this->assertGreaterThan(110, $distance);
        $this->assertLessThan(140, $distance);
    }

    public function test_calculate_distance_returns_zero_for_same_point(): void
    {
        $lat = -6.2088;
        $lng = 106.8456;

        $distance = $this->geoService->calculateDistance($lat, $lng, $lat, $lng);

        $this->assertEquals(0, $distance);
    }

    public function test_get_bounding_box_returns_correct_bounds(): void
    {
        $lat = -6.2088;
        $lng = 106.8456;
        $radius = 50; // km

        $bounds = $this->geoService->getBoundingBox($lat, $lng, $radius);

        $this->assertArrayHasKey('min_lat', $bounds);
        $this->assertArrayHasKey('max_lat', $bounds);
        $this->assertArrayHasKey('min_lng', $bounds);
        $this->assertArrayHasKey('max_lng', $bounds);

        // Verify that min < max
        $this->assertLessThan($bounds['max_lat'], $bounds['min_lat']);
        $this->assertLessThan($bounds['max_lng'], $bounds['min_lng']);

        // Verify center is approximately in the middle
        $latCenter = ($bounds['min_lat'] + $bounds['max_lat']) / 2;
        $lngCenter = ($bounds['min_lng'] + $bounds['max_lng']) / 2;
        $this->assertEqualsWithDelta($lat, $latCenter, 0.001);
        $this->assertEqualsWithDelta($lng, $lngCenter, 0.001);
    }

    public function test_get_severity_color_returns_correct_hex(): void
    {
        $this->assertEquals('#DC2626', $this->geoService->getSeverityColor('critical'));
        $this->assertEquals('#DC2626', $this->geoService->getSeverityColor('high'));
        $this->assertEquals('#F59E0B', $this->geoService->getSeverityColor('medium'));
        $this->assertEquals('#10B981', $this->geoService->getSeverityColor('low'));
        $this->assertEquals('#6B7280', $this->geoService->getSeverityColor('unknown'));
    }

    public function test_get_severity_color_is_case_insensitive(): void
    {
        $this->assertEquals('#DC2626', $this->geoService->getSeverityColor('HIGH'));
        $this->assertEquals('#DC2626', $this->geoService->getSeverityColor('Critical'));
    }

    public function test_get_map_config_returns_expected_structure(): void
    {
        $config = $this->geoService->getMapConfig();

        $this->assertArrayHasKey('api_key', $config);
        $this->assertArrayHasKey('center', $config);
        $this->assertArrayHasKey('zoom', $config);
        $this->assertArrayHasKey('max_zoom', $config);
        $this->assertArrayHasKey('min_zoom', $config);

        $this->assertArrayHasKey('lat', $config['center']);
        $this->assertArrayHasKey('lng', $config['center']);

        $this->assertEquals(5, $config['zoom']);
        $this->assertEquals(18, $config['max_zoom']);
        $this->assertEquals(4, $config['min_zoom']);
    }

    public function test_get_map_config_center_is_indonesia(): void
    {
        $config = $this->geoService->getMapConfig();

        // Indonesia is roughly between -11 to 6 latitude and 95 to 141 longitude
        $this->assertGreaterThan(-11, $config['center']['lat']);
        $this->assertLessThan(6, $config['center']['lat']);
        $this->assertGreaterThan(95, $config['center']['lng']);
        $this->assertLessThan(141, $config['center']['lng']);
    }

    public function test_geocode_returns_null_when_api_key_missing(): void
    {
        // When API key is empty, should return null
        config(['services.google.maps_api_key' => '']);
        $service = new GeoService();

        $result = $service->geocode('Jakarta');

        $this->assertNull($result);
    }

    public function test_reverse_geocode_returns_null_when_api_key_missing(): void
    {
        config(['services.google.maps_api_key' => '']);
        $service = new GeoService();

        $result = $service->reverseGeocode(-6.2088, 106.8456);

        $this->assertNull($result);
    }
}
