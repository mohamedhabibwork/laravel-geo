<?php

// config for Habib/LaravelGeo
return [
    'default' => env('GEO_SERVICE', 'google'),

    'services' => [
        'google' => [
            'api_key' => env('GOOGLE_MAPS_API_KEY'),
            'geocode_url' => env('GOOGLE_GEOCODE_URL', 'https://maps.googleapis.com/maps/api/geocode/json'),
            'directions_url' => env('GOOGLE_DIRECTIONS_URL', 'https://maps.googleapis.com/maps/api/directions/json'),
        ],
        'osrm' => [
            'base_url' => env('OSRM_BASE_URL', 'https://router.project-osrm.org'),
        ],
    ],
];
