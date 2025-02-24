<?php

namespace Habib\LaravelGeo\Services;

use Habib\LaravelGeo\Contracts\GeoServiceInterface;
use Illuminate\Support\Facades\Http;

class GoogleGeoService implements GeoServiceInterface
{
    public function __construct(
        protected string $apiKey,
        protected string $geocodeUrl,
        private string $directionsUrl,
    ) {}

    public function geocode(string $address): ?array
    {
        $response = Http::withoutVerifying()->get($this->geocodeUrl, [
            'address' => $address,
            'key' => $this->apiKey,
        ]);

        if ($response->failed()) {
            return null;
        }

        $data = $response->json();
        if ($data['status'] === 'ZERO_RESULTS') {
            return null;
        }

        if (! isset($data['results'][0]['geometry']['location'])) {
            return null;
        }

        return $data['results'][0]['geometry']['location'];
    }

    public function reverseGeocode(array $point): ?array
    {
        $response = Http::withoutVerifying()->get($this->geocodeUrl, [
            'latlng' => implode(',', $this->parsePoint($point)),
            'key' => $this->apiKey,
        ]);

        if ($response->failed()) {
            return null;
        }

        $data = $response->json();
        if ($data['status'] === 'ZERO_RESULTS') {
            return null;
        }

        if (! isset($data['results'][0]['formatted_address'])) {
            return null;
        }

        return ['address' => $data['results'][0]['formatted_address']];
    }

    protected function parsePoint(array $point): array
    {
        // if it has lat and lng keys, return them
        if (isset($point['lat'], $point['lng'])) {
            return [$point['lat'], $point['lng']];
        }
        // if it has 0 and 1 keys, and they are numeric, return them
        if (isset($point[0], $point[1]) && is_numeric($point[0]) && is_numeric($point[1])) {
            return [$point[0], $point[1]];
        }
        // if it has array of array, return the recursive call all array
        if (is_array($point[0])) {
            $points = [];
            foreach ($point as $p) {
                $points[] = $this->parsePoint($p);
            }

            return $points;
        }

        return $point;
    }

    public function getDistance(array $origin, array $destination): ?array
    {
        $response = Http::withoutVerifying()->get($this->directionsUrl, [
            'origin' => implode(',', $this->parsePoint($origin)),
            'destination' => implode(',', $this->parsePoint($destination)),
            'key' => $this->apiKey,
        ]);

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }

    public function getDirections(array $origin, array $destination): ?array
    {
        $response = Http::withoutVerifying()->get($this->directionsUrl, [
            'origin' => implode(',', $this->parsePoint($origin)),
            'destination' => implode(',', $this->parsePoint($destination)),
            'key' => $this->apiKey,
        ]);

        if ($response->failed()) {
            return null;
        }

        return $response->json();
    }
}
