<?php

namespace Umbrella\Services;

class FileLogger implements Logger
{
    private string $fileName = '../../../var/logs/log.txt';

    function log(string $message)
    {
        file_put_contents($this->fileName, $message . PHP_EOL, FILE_APPEND);
    }
}