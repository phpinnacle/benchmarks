<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Controller;
use App\Repository;
use Zend\Diactoros\ResponseFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$emitter = new SapiEmitter();

$controller = new Controller(new Repository(\getenv('DATABASE')));
$factory = new ResponseFactory();

$result = $controller($request);

$response = $factory->createResponse();
$response->getBody()->write(\json_encode($result));
$response->withAddedHeader('content-type', 'application/json');

$emitter->emit($response);
