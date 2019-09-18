<?php

declare(strict_types=1);

namespace App;

use Smf\ConnectionPool\ConnectionPool;
use Smf\ConnectionPool\Connectors\CoroutineMySQLConnector;

class Repository
{
    /**
     * @var string
     */
    private $dsn;

    /**
     * @var ConnectionPool
     */
    private $pool;

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
        if (!$this->pool) {
            $prt = \parse_url($this->dsn);

            $options = [
                'minActive' => 2,
                'maxActive' => 10,
            ];

            $config = [
                'host'        => $prt['host'],
                'user'        => $prt['user'],
                'password'    => $prt['pass'],
                'database'    => \trim($prt['path'], '/'),
            ];

            $this->pool = new ConnectionPool($options, new CoroutineMySQLConnector(), $config);
        }

        $this->pool->init();
    }

    /**
     * @return void
     */
    public function close(): void
    {
        if ($this->pool) {
            $this->pool->close();
        }
    }

    /**
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function paginate(int $page, int $limit): array
    {
        $db = $this->pool->borrow();

        $page = $page > 1 ? $page : 1;
        $offset = ($page - 1) * $limit;

        $result = $db->query(sprintf('SELECT * FROM users LIMIT %d OFFSET %d;', $limit, $offset));

        $this->pool->return($db);

        return $result;
    }
}
