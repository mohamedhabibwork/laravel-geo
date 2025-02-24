# Laravel Geo

A Laravel package that provides a unified interface for working with different geocoding and routing services. Currently supports Google Maps and OSRM (Open Source Routing Machine) APIs.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/mohamedhabibwork/laravel-geo.svg?style=flat-square)](https://packagist.org/packages/mohamedhabibwork/laravel-geo)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/mohamedhabibwork/laravel-geo/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/mohamedhabibwork/laravel-geo/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/mohamedhabibwork/laravel-geo/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/mohamedhabibwork/laravel-geo/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![StyleCI](https://github.styleci.io/repos/mohamedhabibwork/laravel-geo/shield)](https://github.styleci.io/repos/mohamedhabibwork/laravel-geo)
[![Total Downloads](https://img.shields.io/packagist/dt/mohamedhabibwork/laravel-geo.svg?style=flat-square)](https://packagist.org/packages/mohamedhabibwork/laravel-geo)

## Features

- Geocoding and reverse geocoding (Google Maps)
- Distance calculation between two points
- Route directions with detailed path information
- Support for both Google Maps and OSRM services
- Easy to switch between services
- Consistent response format across services

## Installation

You can install the package via composer:

```bash
composer require mohamedhabibwork/laravel-geo
```

Publish the config file:

```bash
php artisan vendor:publish --tag="laravel-geo-config"
```

## Configuration

Add the following environment variables to your `.env` file:

```env
# Default service (google or osrm)
GEO_SERVICE=google

# Google Maps configuration
GOOGLE_MAPS_API_KEY=your-google-maps-api-key

# OSRM configuration (optional)
OSRM_BASE_URL=https://router.project-osrm.org
```

## Usage

### Basic Usage

```php
use Habib\LaravelGeo\Facades\LaravelGeo;

// Get distance between two points
$origin = ['lat' => 25.2048, 'lng' => 55.2708];      // Dubai
$destination = ['lat' => 24.4539, 'lng' => 54.3773]; // Abu Dhabi

$distance = LaravelGeo::getDistance($origin, $destination);
// Returns: ['distance' => 139482, 'duration' => 5184] (distance in meters, duration in seconds)

// Get directions
$directions = LaravelGeo::getDirections($origin, $destination);
// Returns detailed route information including path geometry
```

### Google Maps Service

```php
// Geocoding
$address = 'Burj Khalifa, Dubai';
$location = LaravelGeo::geocode($address);
// Returns: ['lat' => 25.197197, 'lng' => 55.274376]

// Reverse Geocoding
$point = ['lat' => 25.197197, 'lng' => 55.274376];
$address = LaravelGeo::reverseGeocode($point);
// Returns: ['address' => 'Burj Khalifa - Dubai - United Arab Emirates']
```

### OSRM Service

```php
// Note: OSRM doesn't support geocoding/reverse geocoding

// Get distance with OSRM
config(['geo.default' => 'osrm']);

$origin = ['lat' => 25.2048, 'lng' => 55.2708];
$destination = ['lat' => 24.4539, 'lng' => 54.3773];

$distance = LaravelGeo::getDistance($origin, $destination);
// Returns: ['distance' => 139482, 'duration' => 5184]

// Get detailed directions
$directions = LaravelGeo::getDirections($origin, $destination);
// Returns route information in OSRM format
```

### Coordinate Formats

Both services support two coordinate formats:

1. Associative array:
```php
$point = ['lat' => 25.2048, 'lng' => 55.2708];
```

2. Indexed array:
```php
$point = [25.2048, 55.2708]; // [latitude, longitude]
```

## Code Style

This package follows the Laravel coding style using StyleCI. The configuration can be found in `.styleci.yml`. The following style rules are enabled:

- Method separation
- Aligned double arrows
- Ordered imports (alphabetically)
- Concatenation with spaces

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Mohamed Habib](https://github.com/mohamedhabibwork)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
