<?php

namespace Umbrella\Entry_Points\Cli\Adapters;

use Umbrella\Services\FileCache;
use function Umbrella\Core\Cases\clearCache;

class ClearCache extends Adapter
{

    function execute($input)
    {
        clearCache(new FileCache());

        echo('Cache cleared');
    }
}