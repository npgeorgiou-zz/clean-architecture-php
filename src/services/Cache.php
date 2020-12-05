<?php

namespace Umbrella\Services;

use Umbrella\Core\Models\Location;

interface Cache
{
    function forecastsFor(Location $location): array;
    function saveForecastsFor(Location $location, array $forecasts):void;
    function clear():void;
}