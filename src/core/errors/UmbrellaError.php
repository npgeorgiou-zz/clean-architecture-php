<?php

namespace Umbrella\Core\Errors;

class UmbrellaError extends \Exception
{

    function __construct($message = 'An error happened')
    {
        parent::__construct($message, 0, null);
    }
}
