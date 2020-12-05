<?php

namespace Umbrella\Entry_Points\Web\Adapters;

use Exception;
use Umbrella\Core\Errors\UnknownLocation;
use Umbrella\Core\Errors\UnknownUnit;
use Umbrella\Services\FileCache;
use Umbrella\Services\FileLogger;
use Umbrella\Services\OpenWeatherWeatherman;
use function Umbrella\Core\Cases\locationsThatTomorrowWillBeHotterThan;

class FilterHotLocations extends Adapter
{

    function execute($input)
    {
        if (!isset($input['cityIds']) || !isset($input['unit']) || !isset($input['value'])) {
            die('Missing input');
        }

        $cityIds = explode(',', $input['cityIds']);
        $unit = $input['unit'];
        $value = $input['value'];

        // Prepare and inject Case's dependencies
        $logger = new FileLogger();
        $cache = new FileCache();
        $weatherMan = new OpenWeatherWeatherman($logger);

        try {
            $locations = locationsThatTomorrowWillBeHotterThan($value, $unit, $cityIds, $cache, $weatherMan);
        } catch (UnknownUnit $e) {
            die ('Please make sure to specify a unit according to the docs.');
        } catch (UnknownLocation $e) {
            die ('Please make sure to specify a location id according to the docs.');
        } catch (Exception $e) {
            // If we are here something we didnt throw ourselves happened. Log it.
            $this->logException($e, $logger);
            die ('Please try again later.');
        }

        echo(json_encode($locations, JSON_PRETTY_PRINT));
    }
}