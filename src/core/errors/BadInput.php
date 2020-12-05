<?php


namespace Umbrella\Core\Errors;

class BadInput extends UmbrellaError
{
    public function __construct($message = 'Bad input')
    {
        parent::__construct($message);
    }
}
