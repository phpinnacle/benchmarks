<?php

use App\Controller;
use App\Repository;
use League\Route\Router;
use League\Route\Strategy\JsonStrategy;
use Spiral\Goridge\StreamRelay;
use Spiral\RoadRunner\Worker;
use Spiral\RoadRunner\PSR7Client;
use Zend\Diactoros\ResponseFactory;

ini_set('display_errors', 'stderr');

require __DIR__ . '/vendor/autoload.php';

$relay  = new StreamRelay(\STDIN, \STDOUT);
$worker = new Worker($relay);
$client = new PSR7Client($worker);

$controller = new Controller(new Repository(\getenv('DATABASE')));
$strategy = new JsonStrategy(new ResponseFactory());

while ($request = $client->acceptRequest()) {
    $router = new Router();
    $router->setStrategy($strategy);
    $router->map('GET', '/', $controller);

    try {
        $client->respond($router->dispatch($request));
    } catch (\Throwable $e) {
        $worker->error((string)$e);
    }
}
