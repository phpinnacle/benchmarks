<?php

use App\Controller;
use App\Repository;
use League\Route\Router;
use Spiral\Goridge\StreamRelay;
use Spiral\RoadRunner\Worker;
use Spiral\RoadRunner\PSR7Client;

ini_set('display_errors', 'stderr');

require __DIR__ . '/vendor/autoload.php';

$relay = new StreamRelay(\STDIN, \STDOUT);
$psr7 = new PSR7Client(new Worker($relay));

$router = new Router();
$router->setStrategy($strategy);

$repository = new Repository(\getenv('DATABASE'));
$controller = new Controller($repository);

$router->map('GET', '/', $controller);

while ($req = $psr7->acceptRequest()) {
    try {
        $psr7->respond($router->dispatch($request));
    } catch (\Throwable $e) {
        $psr7->getWorker()->error((string)$e);
    }
}
