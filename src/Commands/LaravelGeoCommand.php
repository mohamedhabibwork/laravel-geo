<?php

namespace Habib\LaravelGeo\Commands;

use Illuminate\Console\Command;

class LaravelGeoCommand extends Command
{
    public $signature = 'laravel-geo';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
