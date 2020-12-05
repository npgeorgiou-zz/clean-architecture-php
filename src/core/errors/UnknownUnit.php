<?php

namespace Umbrella\Core\Errors;

class UnknownUnit extends UmbrellaError
{
    public function __construct($message = 'Unknown unit')
    {
        parent::__construct($message);
    }
}
