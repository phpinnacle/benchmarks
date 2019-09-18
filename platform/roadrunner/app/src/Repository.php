<?php

declare(strict_types=1);

namespace App;

class Repository
{
    /**
     * @var string
     */
    private $dsn;

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var bool
     */
    private $connected = false;

    /**
     * @param string $dsn
     */
    public function __construct(string $dsn)
    {
        $this->dsn = $dsn;
    }

    /**
     * @return void
     */
    public function connect(): void
    {
        if ($this->connected) {
            return;
        }

        $prt = \parse_url($this->dsn);

        $this->pdo = new \PDO(
            \sprintf('mysql:dbname=%s;host=%s', \trim($prt['path'], '/'), $prt['host']),
            $prt['user'], $prt['pass']
        );

        $this->connected = true;
    }

    /**
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function paginate(int $page, int $limit): array
    {
        $this->connect();

        $page = $page > 1 ? $page : 1;
        $offset = ($page - 1) * $limit;

        $stmt = $this->pdo->prepare('SELECT * FROM users LIMIT :limit OFFSET :offset;');

        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
