<?php

namespace App;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitmqConnectionManger
{
    private const PORT = 5672;
    private const HOST = 'rabbitmq';
    private const USER = 'guest';
    private const PASSWORD = 'guest';

    /** @var AMQPStreamConnection $connection */
    private $connection;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(self::HOST, self::PORT, self::USER, self::PASSWORD);
    }

    public function getConnection(): AMQPStreamConnection
    {
        return $this->connection;
    }
}