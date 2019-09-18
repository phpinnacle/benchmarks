<?php

declare(strict_types=1);

namespace App;

use Amp\Mysql\ConnectionConfig;
use Amp\Mysql\ResultSet;
use Amp\Mysql\Statement;
use Amp\Promise;
use Amp\Sql\Pool;
use Psr\Log\LoggerInterface;
use function Amp\call;
use function Amp\Mysql\pool;

class Repository
{
    /**
     * @var string
     */
    private $dsn;

    /**
     * @var Pool
     */
    private $pool;

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

        $this->pool = pool(ConnectionConfig::fromString($this->dsn));

        $this->connected = true;
    }

    /**
     * @param int $page
     * @param int $limit
     *
     * @return Promise<array>
     */
    public function paginate(int $page, int $limit): Promise
    {
        $this->connect();

        return call(function () use ($page, $limit) {
            $page = $page > 1 ? $page : 1;
            $offset = ($page - 1) * $limit;

            /** @var ResultSet $result */
            $result = yield $this->pool->execute('SELECT * FROM users LIMIT :limit OFFSET :offset;', [
                'limit' => $limit,
                'offset' => $offset,
            ]);

            $rows = [];

            while (yield $result->advance()) {
                $rows[] = $result->getCurrent();
            }

            return $rows;
        });
    }
}
