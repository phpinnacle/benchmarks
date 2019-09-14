<?php

namespace App;

use Psr\Http\Message\RequestInterface;

class Controller
{
    private $repository;
    
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }
    
    public function __invoke(RequestInterface $request)
    {
        \parse_str($request->getUri()->getQuery(), $query);

        $items = $this->repository->paginate($query['page'] ?? 1, $query['limit'] ?? 30);
        
        return \array_map(function (array $item) {
            return [
                'id' => (int) $item['id'],
                'name' => \sprintf('%s %s', $item['first_name'], $item['last_name']),
            ];
        }, $items);
    }
}
