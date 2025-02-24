<?php

namespace Habib\LaravelGeo\Contracts;

interface GeoServiceInterface
{
    public function geocode(string $address): ?array;

    /**
     * @param  array{lat: float, lng: float}|array{0: float, 1: float}  $point
     */
    public function reverseGeocode(array $point): ?array;

    /**
     * @param  array{lat: float, lng: float}|array{0: float, 1: float}  $origin
     * @param  array{lat: float, lng: float}|array{0: float, 1: float}  $destination
     */
    public function getDistance(array $origin, array $destination): ?array;

    /**
     * @param  array{lat: float, lng: float}|array{0: float, 1: float}  $origin
     * @param  array{lat: float, lng: float}|array{0: float, 1: float}  $destination
     */
    public function getDirections(array $origin, array $destination): ?array;
}
