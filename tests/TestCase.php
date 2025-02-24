<?php

namespace Habib\LaravelGeo\Tests;

use Habib\LaravelGeo\LaravelGeoServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Habib\\LaravelGeo\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelGeoServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        // Set up mock configurations for geo services
        config()->set('geo.services.google.api_key', 'test-api-key');
        config()->set('geo.services.google.geocode_url', 'https://maps.googleapis.com/maps/api/geocode/json');
        config()->set('geo.services.google.directions_url', 'https://maps.googleapis.com/maps/api/directions/json');
        config()->set('geo.services.osrm.base_url', 'https://router.project-osrm.org');
    }
}
