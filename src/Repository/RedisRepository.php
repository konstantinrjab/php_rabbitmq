<?php

namespace App\Repository;

use App\Entity\SearchResult;
use Redis;

class RedisRepository
{
    /** @var Redis $redis */
    private $redis;

    public function __construct()
    {
        $this->redis = new Redis();
        $this->redis->pconnect('redis');
    }

    public function insert(string $key, string $value): void
    {
        $this->redis->set($key, $value);
    }

    public function get(string $key): ?SearchResult
    {
        $result = $this->redis->get($key);
        if (!$result) {
            return null;
        }

        return unserialize($result);
    }
}
