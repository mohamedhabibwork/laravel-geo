<?php

namespace Habib\LaravelGeo\Tests\Services;

use Habib\LaravelGeo\Services\OSRMGeoService;
use Habib\LaravelGeo\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class OSRMGeoServiceTest extends TestCase
{
    private OSRMGeoService $geoService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->geoService = new OSRMGeoService(
            baseUrl: rtrim(config('geo.services.osrm.base_url'), '/')
        );
    }

    public function test_get_directions_returns_route_for_valid_coordinates()
    {
        Http::fake([
            '*' => Http::response([
                'code' => 'Ok',
                'routes' => [
                    [
                        'distance' => 1000.0,
                        'duration' => 300.0,
                        'geometry' => 'encoded_polyline_string',
                    ],
                ],
            ]),
        ]);

        $result = $this->geoService->getDirections(
            ['lat' => 40.7128, 'lng' => -74.0060],
            ['lat' => 34.0522, 'lng' => -118.2437]
        );

        $this->assertIsArray($result);
        $this->assertEquals(1000.0, $result['distance']);
        $this->assertEquals(300.0, $result['duration']);
        $this->assertEquals('encoded_polyline_string', $result['geometry']);
    }

    public function test_get_directions_returns_null_for_invalid_coordinates()
    {
        Http::fake([
            '*' => Http::response([
                'code' => 'NoRoute',
            ]),
        ]);

        $result = $this->geoService->getDirections(
            ['lat' => 0, 'lng' => 0],
            ['lat' => 0, 'lng' => 0]
        );

        $this->assertNull($result);
    }

    public function test_get_directions_handles_connection_error()
    {
        Http::fake([
            '*' => Http::response(null, 500),
        ]);

        $result = $this->geoService->getDirections(
            ['lat' => 40.7128, 'lng' => -74.0060],
            ['lat' => 34.0522, 'lng' => -118.2437]
        );

        $this->assertNull($result);
    }

    public function test_get_directions_handles_invalid_response_format()
    {
        Http::fake([
            '*' => Http::response([
                'invalid' => 'response',
            ]),
        ]);

        $result = $this->geoService->getDirections(
            ['lat' => 40.7128, 'lng' => -74.0060],
            ['lat' => 34.0522, 'lng' => -118.2437]
        );

        $this->assertNull($result);
    }
}
