<?php

require __DIR__ . '/vendor/autoload.php';

use Amp\Http\Server\RequestHandler\CallableRequestHandler;
use Amp\Http\Server\Router;
use Amp\Http\Server\Server;
use Amp\Http\Server\Request;
use Amp\Http\Server\Response;
use Amp\Http\Status;
use Amp\Socket;
use App\Controller;
use App\Repository;
use Psr\Log\NullLogger;

Amp\Loop::run(function () {
    $repository = new Repository(\getenv('DATABASE'));
    $controller = new Controller($repository);

    $sockets = [
        Socket\listen("0.0.0.0:9000"),
        Socket\listen("[::]:9000"),
    ];

    $router = new Router;
    $router->addRoute('GET', '/', new CallableRequestHandler(function (Request $request) use ($controller) {
        $result = yield $controller($request);

        return new Response(Status::OK, ['content-type' => 'application/json'], \json_encode($result));
    }));

    $server = new Server($sockets, $router, new NullLogger());

    yield $server->start();

    Amp\Loop::onSignal(\SIGINT, function (string $watcherId) use ($server) {
        Amp\Loop::cancel($watcherId);

        yield $server->stop();
    });
});
