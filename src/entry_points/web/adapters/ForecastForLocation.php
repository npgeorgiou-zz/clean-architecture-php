<?php

namespace Umbrella\Entry_Points\Web\Adapters;

use Exception;
use Umbrella\Core\Errors\UnknownLocation;
use Umbrella\Core\Errors\UnknownUnit;
use Umbrella\Services\FileCache;
use Umbrella\Services\FileLogger;
use Umbrella\Services\OpenWeatherWeatherman;
use function Umbrella\Core\Cases\temperatureForNext5DaysFor;

class ForecastForLocation extends Adapter
{

    function execute($input)
    {
        if (!isset($input['cityId']) || !isset($input['unit'])) {
            die('Missing input');
        }

        $cityId = $input['cityId'];
        $unit = $input['unit'];

        // Prepare and inject Case's dependencies.
        $logger = new FileLogger();
        $cache = new FileCache();
        $weatherMan = new OpenWeatherWeatherman($logger);

        try {
            $forecasts = temperatureForNext5DaysFor($cityId, $unit, $cache, $weatherMan);
        } catch (UnknownUnit $e) {
            die ('Please make sure to specify a unit according to the docs.');
        } catch (UnknownLocation $e) {
            die ('Please make sure to specify a location id according to the docs.');
        } catch (Exception $e) {
            // If we are here something we didnt throw ourselves happened. Log it.
            $this->logException($e, $logger);
            die ('Please try again later.');
        }

        die(json_encode($forecasts, JSON_PRETTY_PRINT));
    }
}
