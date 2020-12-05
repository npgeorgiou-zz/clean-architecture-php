<?php
namespace Umbrella\Entry_Points\Web;

use Umbrella\Entry_Points\Web\Adapters\FilterHotLocations;
use Umbrella\Entry_Points\Web\Adapters\ForecastForLocation;

require __DIR__ . '/../../../vendor/autoload.php';


$method = $_SERVER['REQUEST_METHOD'];

$uriAndParams = str_replace('/main.php', '', $_SERVER['REQUEST_URI']);
$uri = explode('?', $uriAndParams)[0];
parse_str(explode('?', $uriAndParams)[1], $params);

$endpoint = "$method:$uri";

$routes = [
    'GET:/forecast-for-location' => new ForecastForLocation(),
    'GET:/filter-hot-locations' => new FilterHotLocations()
];

if (!isset($routes[$endpoint])) {
    die("Unknown endpoint $endpoint");
}

$handler = $routes[$endpoint];
$handler->execute($params);
