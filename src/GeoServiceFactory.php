<?php

namespace Habib\LaravelGeo;

use Habib\LaravelGeo\Contracts\GeoServiceInterface;
use Habib\LaravelGeo\Services\GoogleGeoService;
use Habib\LaravelGeo\Services\OSRMGeoService;
use InvalidArgumentException;

class GeoServiceFactory
{
    public static function create(string $service): GeoServiceInterface
    {
        return match ($service) {
            'google' => app(GoogleGeoService::class),
            'osrm' => app(OSRMGeoService::class),
            default => throw new InvalidArgumentException("Invalid geocoding service: $service"),
        };
    }
}
