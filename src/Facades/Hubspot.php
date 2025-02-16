<?php

namespace DionBoerrigter\Hubspot\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DionBoerrigter\Hubspot\Hubspot
 */
class Hubspot extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \DionBoerrigter\Hubspot\Hubspot::class;
    }
}
