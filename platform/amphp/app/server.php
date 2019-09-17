<?php

require __DIR__ . '/vendor/autoload.php';

use Amp\ByteStream;
use Amp\Cluster\Cluster;
use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler\CallableRequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Server\Server;
use Amp\Http\Status;
use Amp\Log\ConsoleFormatter;
use Amp\Log\StreamHandler;
use Amp\Promise;
use App\Controller;
use App\Repository;
use Monolog\Logger;

Amp\Loop::run(function () {
    $repository = new Repository(\getenv('DATABASE'));
    $controller = new Controller($repository);

    $sockets = yield [
        Cluster::listen("0.0.0.0:8080"),
        Cluster::listen("[::]:8080"),
    ];

    if (Cluster::isWorker()) {
        $handler = Cluster::createLogHandler();
    } else {
        $handler = new StreamHandler(ByteStream\getStdout());
        $handler->setFormatter(new ConsoleFormatter);
    }

    $logger = new Logger('worker-' . Cluster::getId());
    $logger->pushHandler($handler);

    $server = new Server($sockets, new CallableRequestHandler(function (Request $request): Response {
        return new Response(Status::OK, [
            "content-type" => "text/plain; charset=utf-8"
        ], "Hello, World!");
    }), $logger);

    yield $server->start();

    Cluster::onTerminate(function () use ($server): Promise {
        return $server->stop();
    });
});
