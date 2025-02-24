<?php

namespace Habib\LaravelGeo\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Habib\LaravelGeo\LaravelGeo
 */
class LaravelGeo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Habib\LaravelGeo\Contracts\GeoServiceInterface::class;
    }
}
