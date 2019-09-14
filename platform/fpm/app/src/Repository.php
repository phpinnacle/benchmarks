<?php

namespace App;

class Repository
{
    private $pdo;

    public function __construct(string $dsn)
    {
        $dsn = \getenv('DATABASE');
        $prt = \parse_url($dsn);
    
        $this->pdo = new \PDO(
            \sprintf('mysql:dbname=%s;host=%s', \trim($prt['path'], '/'), $prt['host']),
            $prt['user'], $prt['pass']
        );
    }

    public function paginate(int $page, int $limit): array
    {
        $page = $page > 1 ? $page : 1;
        $offset = ($page - 1) * $limit;

        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE status = 1 LIMIT :limit OFFSET :offset;');

        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
