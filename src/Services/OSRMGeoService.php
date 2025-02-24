<?php

namespace Habib\LaravelGeo\Services;

use Habib\LaravelGeo\Contracts\GeoServiceInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class OSRMGeoService implements GeoServiceInterface
{
    public function __construct(
        protected string $baseUrl,
        protected string $profile = 'car',
    ) {}

    public function geocode(string $address): ?array
    {
        throw new InvalidArgumentException('OSRM doesn\'t support geocoding');
    }

    public function reverseGeocode(array $point): ?array
    {
        throw new InvalidArgumentException('OSRM doesn\'t support reverse geocoding');
    }

    public function getDistance(array $origin, array $destination): ?array
    {
        $coordinates = $this->formatCoordinates($origin, $destination);
        $url = "{$this->baseUrl}/route/v1/{$this->profile}/{$coordinates}?overview=false";

        if (! $response = $this->makeRequest($url)) {
            return null;
        }

        if (!isset($response['code']) || $response['code'] !== 'Ok' || empty($response['routes'])) {
            return null;
        }

        return [
            'distance' => $response['routes'][0]['distance'], // in meters
            'duration' => $response['routes'][0]['duration'], // in seconds
        ];
    }

    public function getDirections(array $origin, array $destination): ?array
    {
        $coordinates = $this->formatCoordinates($origin, $destination);
        $url = "{$this->baseUrl}/route/v1/{$this->profile}/{$coordinates}?overview=full&geometries=geojson";

        if (!$response = $this->makeRequest($url)) {
            return null;
        }

        if (!isset($response['code']) || $response['code'] !== 'Ok' || empty($response['routes'])) {
            return null;
        }

        return $response['routes'][0];
    }

    protected function formatCoordinates(array $origin, array $destination): string
    {
        return implode(';', [
            $this->pointToString($origin),
            $this->pointToString($destination),
        ]);
    }

    protected function pointToString(array $point): string
    {
        $lngLat = $this->extractLngLat($point);

        return "{$lngLat['lng']},{$lngLat['lat']}";
    }

    protected function extractLngLat(array $point): array
    {
        if (isset($point['lat'], $point['lng'])) {
            return ['lat' => $point['lat'], 'lng' => $point['lng']];
        }

        if (isset($point[0], $point[1])) {
            return ['lat' => $point[0], 'lng' => $point[1]];
        }

        throw new InvalidArgumentException('Invalid coordinate format');
    }

    /**
     * @throws ConnectionException
     */
    protected function makeRequest(string $url): ?array
    {
        $response = Http::withoutVerifying()->asJson()->acceptJson()->get($url);

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }
}
