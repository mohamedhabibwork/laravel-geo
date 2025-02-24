<?php

namespace Habib\LaravelGeo;

use Habib\LaravelGeo\Commands\LaravelGeoCommand;
use Habib\LaravelGeo\Contracts\GeoServiceInterface;
use Habib\LaravelGeo\Exceptions\GeoServiceException;
use Habib\LaravelGeo\Services\GoogleGeoService;
use Habib\LaravelGeo\Services\OSRMGeoService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelGeoServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-geo')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommand(LaravelGeoCommand::class);

        $this->app->singleton(GeoServiceInterface::class, function ($app) {
            $driver = config('geo.default');

            return match ($driver) {
                'google' => new GoogleGeoService(
                    apiKey: config('geo.services.google.api_key'),
                    geocodeUrl: config('geo.services.google.geocode_url', 'https://maps.googleapis.com/maps/api/geocode/json'),
                    directionsUrl: config('geo.services.google.directions_url', 'https://maps.googleapis.com/maps/api/directions/json')
                ),
                'osrm' => new OSRMGeoService(
                    baseUrl: rtrim(config('geo.services.osrm.base_url', 'https://router.project-osrm.org'), '/')
                ),
                default => throw new GeoServiceException("Unsupported driver: {$driver}"),
            };
        });
    }
}
