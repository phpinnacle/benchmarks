<?php

use App\Controller;
use App\Repository;
use Spiral\Goridge\Relay;
use Spiral\RoadRunner\Worker;
use Spiral\RoadRunner\PSR7Client;
use Zend\Diactoros\ResponseFactory;

ini_set('display_errors', 'stderr');

require __DIR__ . '/vendor/autoload.php';

$relay = Relay::pipes();
$worker = new Worker($relay);
$client = new PSR7Client($worker);

$controller = new Controller(new Repository(\getenv('DATABASE')));
$factory = new ResponseFactory();

while ($request = $client->accept()) {
    try {
        $result = $controller($request);

        $response = $factory->createResponse();
        $response->getBody()->write(\json_encode($result));
        $response->withAddedHeader('content-type', 'application/json');

        $client->respond($response);
    } catch (\Throwable $e) {
        $client->error($e);
    }
}
