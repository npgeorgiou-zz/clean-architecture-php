<?php

namespace Umbrella\Core\Models;

class Location
{
    public int $cityId;

    function __construct(int $cityId) {
        $this->cityId = $cityId;
    }
}