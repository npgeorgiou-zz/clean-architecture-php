<?php

namespace Umbrella\Services;

use Umbrella\Core\Errors\UnknownLocation;
use Umbrella\Core\Models\Location;

interface Weatherman
{

    /**
     * @throws UnknownLocation
     */
    function forecastsFor(Location $location);
}