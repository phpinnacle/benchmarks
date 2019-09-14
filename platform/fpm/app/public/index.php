<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use App\Controller;
use App\Repository;
use League\Route\Strategy\JsonStrategy;
use League\Route\Router;
use Zend\Diactoros\ResponseFactory;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
);

$emitter = new SapiEmitter();

$factory = new ResponseFactory();
$strategy = new JsonStrategy($factory);

$router = new Router();
$router->setStrategy($strategy);

$repository = new Repository(\getenv('DATABASE'));
$controller = new Controller($repository);

$router->map('GET', '/', $controller);

$response = $router->dispatch($request);
$emitter->emit($response);
