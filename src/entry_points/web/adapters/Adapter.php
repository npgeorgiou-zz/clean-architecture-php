<?php

namespace Umbrella\Entry_Points\Web\Adapters;

use Exception;
use Umbrella\Services\Logger;

abstract class Adapter
{
    public abstract function execute(array $input);

    protected function logException(Exception $e, Logger $logger){
        $message = $e->getMessage();
        $trace = $e->getTraceAsString();

        $logger->log("EXCEPTION: $message. $trace");
    }
}