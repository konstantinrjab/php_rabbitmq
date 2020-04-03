<?php

namespace App\Database;

use Redis;

class RedisConnectionManger
{
    /** @var Redis $connection */
    private static $connection;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getConnection(): Redis
    {
        if (self::$connection) {
            return self::$connection;
        }
        $redis = new Redis();
        $redis->pconnect('php_async_redis');
        self::$connection = $redis;

        return self::$connection;
    }
}
