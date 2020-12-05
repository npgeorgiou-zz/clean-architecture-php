<?php

namespace Umbrella\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Umbrella\Core\Errors\UmbrellaError;
use Umbrella\Core\Errors\UnknownLocation;
use Umbrella\Core\Models\Forecast;
use Umbrella\Core\Models\Location;

class OpenWeatherWeatherman implements Weatherman
{

    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @throws UmbrellaError
     * @throws UnknownLocation
     */
    function forecastsFor(Location $location)
    {
        $cityId = $location->cityId;
        $apiKey = '05faaa394de5c63d7a70a722f8c17b5e'; // Normally we 'd read that from ome env value.
        $endpoint = "http://api.openweathermap.org/data/2.5/forecast?id=$cityId&appid=$apiKey";

        $client = new Client();

        try {
            $response = $client->request('GET', $endpoint);
        } catch (ClientException $e) {
            // A usual error can be that the location is wrong
            if ($e->getResponse()->getStatusCode() === 404) {
                throw new UnknownLocation();
            }

            // Who knows what other errors might be thrown...Log for examination.
            $code = $e->getResponse()->getStatusCode();
            $contents = $e->getResponse()->getBody()->getContents();
            $this->logger->log("$endpoint resulted in $code:$contents");

            // Rethrow to let outer layers handle.
            throw $e;
        }

        $responseContent = json_decode($response->getBody()->getContents());
        $forecasts = $responseContent->list;

        // Cast to Umbrella Forecasts. Api returns lots of spammy data, and its a way to get only what we need.
        return array_map(function($forecast) {
            return new Forecast(
                $forecast->dt,
                $forecast->dt + 3600 * 3,
                Forecast::KELVIN,
                $forecast->main->temp
            );
        }, $forecasts);
    }
}