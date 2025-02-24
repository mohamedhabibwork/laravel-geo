<?php

namespace Habib\LaravelGeo\Tests\Services;

use Habib\LaravelGeo\Services\GoogleGeoService;
use Habib\LaravelGeo\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class GoogleGeoServiceTest extends TestCase
{
    private GoogleGeoService $geoService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->geoService = new GoogleGeoService(
            apiKey: config('geo.services.google.api_key'),
            geocodeUrl: config('geo.services.google.geocode_url'),
            directionsUrl: config('geo.services.google.directions_url')
        );
    }

    public function test_geocode_returns_coordinates_for_valid_address()
    {
        Http::fake([
            '*' => Http::response([
                'status' => 'OK',
                'results' => [
                    [
                        'geometry' => [
                            'location' => [
                                'lat' => 40.7128,
                                'lng' => -74.0060,
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $result = $this->geoService->geocode('New York, NY');

        $this->assertIsArray($result);
        $this->assertEquals(40.7128, $result['lat']);
        $this->assertEquals(-74.0060, $result['lng']);
    }

    public function test_geocode_returns_null_for_invalid_address()
    {
        Http::fake([
            '*' => Http::response([
                'status' => 'ZERO_RESULTS',
            ]),
        ]);

        $result = $this->geoService->geocode('Invalid Address XYZ');

        $this->assertNull($result);
    }

    public function test_reverse_geocode_returns_address_for_valid_coordinates()
    {
        Http::fake([
            '*' => Http::response([
                'status' => 'OK',
                'results' => [
                    [
                        'formatted_address' => '1600 Pennsylvania Avenue NW, Washington, DC 20500',
                    ],
                ],
            ]),
        ]);

        $result = $this->geoService->reverseGeocode(['lat' => 38.8977, 'lng' => -77.0365]);

        $this->assertIsArray($result);
        $this->assertEquals('1600 Pennsylvania Avenue NW, Washington, DC 20500', $result['address']);
    }

    public function test_reverse_geocode_returns_null_for_invalid_coordinates()
    {
        Http::fake([
            '*' => Http::response([
                'status' => 'ZERO_RESULTS',
            ]),
        ]);

        $result = $this->geoService->reverseGeocode(['lat' => 0, 'lng' => 0]);

        $this->assertNull($result);
    }
}
