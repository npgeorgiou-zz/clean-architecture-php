<?php
namespace Umbrella\Entry_Points\Web;

use Umbrella\Entry_Points\Cli\Adapters\ClearCache;

require __DIR__ . '/../../../vendor/autoload.php';


$command = $argv[1];

$commands = [
    'clear-cache' => new ClearCache()
];

if (!isset($commands[$command])) {
    die("Unknown command $command");
}

$handler = $commands[$command];
$handler->execute($argv);
