<?php

namespace App\Database;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitmqConnectionManger
{
    private const PORT = 5672;
    private const HOST = 'php_async_rabbitmq';
    private const USER = 'guest';
    private const PASSWORD = 'guest';

    /** @var AMQPStreamConnection $connection */
    private static $connection;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getConnection(): AMQPStreamConnection
    {
        if (self::$connection) {
            return self::$connection;
        }
        self::$connection = new AMQPStreamConnection(self::HOST, self::PORT, self::USER, self::PASSWORD);

        return self::$connection;
    }
}
