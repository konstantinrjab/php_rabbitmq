<?php

namespace App\Repository;

use Redis;

class RedisRepository
{
    public function insert(string $key, $value): void
    {
        $redis = new Redis();
        $redis->pconnect('redis');

        $redis->set($key, $value);
    }
}
