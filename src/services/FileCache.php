<?php

namespace Umbrella\Services;

use Umbrella\Core\Models\Location;

class FileCache implements Cache
{
    private string $fileName = '../../../var/cache/forecasts.json';

        function forecastsFor(Location $location): array
    {
        $contents = file_get_contents($this->fileName);
        $allForecasts = unserialize($contents);

        if (!isset($allForecasts[$location->cityId])) {
            return [];
        }

        return $allForecasts[$location->cityId];
    }

    function saveForecastsFor(Location $location, array $forecasts): void
    {
        $contents = file_get_contents($this->fileName);
        $allForecasts = unserialize($contents);

        $allForecasts[$location->cityId] = $forecasts;

        file_put_contents($this->fileName, serialize($allForecasts));
    }

    function clear(): void
    {
        file_put_contents($this->fileName, serialize([]));
    }
}