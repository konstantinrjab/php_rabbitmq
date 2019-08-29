<?php

namespace App\Helper;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitmqConnectionHelper
{
    static private $connection = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    public static function getConnection(): AMQPStreamConnection
    {
        if (self::$connection === null) {
            self::$connection = self::createConnection();
        }

        return self::$connection;
    }

    private static function createConnection(): AMQPStreamConnection
    {
        return new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
    }
}
