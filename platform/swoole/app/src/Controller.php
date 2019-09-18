<?php

declare(strict_types=1);

namespace App;

use Swoole\Http\Request;
use Swoole\Http\Response;

class Controller
{
    /**
     * @var Repository
     */
    private $repository;

    /**
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request  $request
     * @param Response $response
     */
    public function __invoke(Request $request, Response $response)
    {
        $items = $this->repository->paginate(\random_int(1, 199), 50);
        $items = \array_map(function (array $item) {
            return [
                'id' => (int) $item['id'],
                'name' => \sprintf('%s %s', $item['first_name'], $item['last_name']),
            ];
        }, $items);

        $response->header('Content-Type', 'application/json');
        $response->end(\json_encode($items));
    }
}
