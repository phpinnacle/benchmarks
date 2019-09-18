<?php

declare(strict_types=1);

namespace App;

use Amp\Http\Server\Request;
use Amp\Promise;
use function Amp\call;

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
     * @param Request $request
     *
     * @return array
     */
    public function __invoke(Request $request): Promise
    {
        return call(function () use ($request) {
            \parse_str($request->getUri()->getQuery(), $query);

            $items = yield $this->repository->paginate($query['page'] ?? \random_int(1, 199), $query['limit'] ?? 50);

            return \array_map(function (array $item) {
                return [
                    'id' => (int) $item['id'],
                    'name' => \sprintf('%s %s', $item['first_name'], $item['last_name']),
                ];
            }, $items);
        });
    }
}
