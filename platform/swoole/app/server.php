<?php

use App\Controller;
use App\Repository;
use Swoole\Http\Server;

require __DIR__ . '/vendor/autoload.php';

Swoole\Runtime::enableCoroutine(true);

$options = [
    'daemonize'             => false,
    'dispatch_mode'         => 1,
    'max_request'           => 10000,
    'open_tcp_nodelay'      => true,
    'reload_async'          => true,
    'max_wait_time'         => 60,
    'enable_reuse_port'     => true,
    'enable_coroutine'      => true,
    'http_compression'      => false,
    'enable_static_handler' => false,
    'buffer_output_size'    => 4 * 1024 * 1024,
    'worker_num'            => 4, // Each worker holds a connection pool
];

$server = new Server('0.0.0.0', 9000);
$server->set($options);

$repository = new Repository(\getenv('DATABASE'));
$controller = new Controller($repository);

$server->on('WorkerStart', function () use ($repository) {
    $repository->connect();
});
$server->on('WorkerStop', function () use ($repository) {
    $repository->close();
});
$server->on('WorkerError', function () use ($repository) {
    $repository->close();
});
$server->on("request", $controller);

$server->start();
