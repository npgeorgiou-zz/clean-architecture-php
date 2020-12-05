<?php

namespace Umbrella\Core\Models;

use DateTime;

class Forecast
{
    const KELVIN = 'kelvin';
    const CELSIUS = 'celsius';
    const FAHRENHEIT = 'fahrenheit';

    public int $from;
    public int $to;
    public string $unit;
    public float $temperature;

    public function __construct(int $from, int $to, string $unit, float $temperature)
    {
        $this->from = $from;
        $this->to = $to;
        $this->unit = $unit;
        $this->temperature = $temperature;
    }

    public function convertTo(string $unit)
    {
        if ($this->unit !== self::KELVIN) {
            return;
        }

        switch ($unit) {
            case self::CELSIUS:
                $this->toCelsius();
                break;
            case self::FAHRENHEIT:
                $this->toFahrenheit();
                break;
        }
    }

    public function toCelsius()
    {
        $this->unit = self::CELSIUS;
        $this->temperature -= 273.15;
    }

    public function toFahrenheit()
    {
        $this->unit = self::FAHRENHEIT;
        $this->temperature = 9 / 5 * ($this->temperature - 273.15) + 32;
    }

    public function belongsToDate(DateTime $date)
    {
        $startOfTomorrow = $date->setTime(0, 0, 0)->getTimestamp();
        $endOfTomorrow = $date->setTime(23, 59, 59)->getTimestamp();

        return $this->from >= $startOfTomorrow && $this->to <= $endOfTomorrow;
    }
}