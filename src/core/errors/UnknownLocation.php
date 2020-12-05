<?php

namespace Umbrella\Core\Errors;

class UnknownLocation extends UmbrellaError
{
    public function __construct($message = 'Unknown location')
    {
        parent::__construct($message);
    }
}
