<?php

namespace Umbrella\Entry_Points\Cli\Adapters;

abstract class Adapter
{
    public abstract function execute(array $input);
}