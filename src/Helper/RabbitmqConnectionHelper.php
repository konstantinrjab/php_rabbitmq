<?php

namespace App\Helper;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitmqConnectionHelper
{
    private const PORT = 5672;
    private const HOST = 'rabbitmq';
    private const USER = 'guest';
    private const PASSWORD = 'guest';

    private static $connection = null;

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
        return new AMQPStreamConnection(self::HOST, self::PORT, self::USER, self::PASSWORD);
    }
}
