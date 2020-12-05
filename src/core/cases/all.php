<?php

namespace Umbrella\Core\Cases;

use DateTime;
use Umbrella\Core\Errors\UnknownLocation;
use Umbrella\Core\Errors\UnknownUnit;
use Umbrella\Core\Models\Forecast;
use Umbrella\Core\Models\Location;
use Umbrella\Core\Models\Temperature;
use Umbrella\Services\Cache;
use Umbrella\Services\Weatherman;

function forecastsAreOld(array $forecasts)
{
    return true;
    // TODO: Make an implementation that checks
    //  whether the first Forecast is older than 3 hours.
}

/**
 * @throws UnknownUnit
 * @throws UnknownLocation
 */
function temperatureForNext5DaysFor(
    int $cityId,
    string $unit,
    Cache $cache,
    Weatherman $weatherman
): array
{
    if ($unit !== Forecast::CELSIUS && $unit !== Forecast::FAHRENHEIT) {
        throw new UnknownUnit();
    }

    $location = new Location($cityId);

    $forecasts = $cache->forecastsFor($location);

    if (!$forecasts || forecastsAreOld($forecasts)) {
        $forecasts = $weatherman->forecastsFor($location);
        $cache->saveForecastsFor($location, $forecasts);
    }

    array_walk($forecasts, fn(Forecast $it) => $it->convertTo($unit));

    return $forecasts;
}

/**
 * @throws UnknownUnit
 * @throws UnknownLocation
 */
function locationsThatTomorrowWillBeHotterThan(
    float $value,
    string $unit,
    array $cityIds,
    Cache $cache,
    Weatherman $weatherman
): array
{
    if ($unit !== Forecast::CELSIUS && $unit !== Forecast::FAHRENHEIT) {
        throw new UnknownUnit();
    }

    $filtered = [];

    foreach ($cityIds as $cityId) {
        $location = new Location($cityId);

        $forecasts = $cache->forecastsFor($location);

        if (!$forecasts || forecastsAreOld($forecasts)) {
            $forecasts = $weatherman->forecastsFor($location);
            $cache->saveForecastsFor($location, $forecasts);
        }

        $tomorrow = new DateTime('tomorrow');
        $tomorrowsForecasts = array_filter($forecasts, fn (Forecast $it) => $it->belongsToDate($tomorrow));

        foreach ($tomorrowsForecasts as $tomorrowsForecast) {
            $tomorrowsForecast->convertTo($unit);
            if ($tomorrowsForecast->temperature > $value) {
                $filtered[] = $location;
                break;
            }
        }
    }

    return $filtered;
}

function clearCache(Cache $cache): void
{
    $cache->clear();
}