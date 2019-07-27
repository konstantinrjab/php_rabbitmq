<?php

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitmqConnection
{
    private const RABBITMQ_PORT = 5672;
    private const RABBITMQ_HOST = 'rabbitmq';
    private const RABBITMQ_USER = 'guest';
    private const RABBITMQ_PASSWORD = 'guest';

    public static function getChannel(): AMQPChannel
    {
        $connection = new AMQPStreamConnection(
            self::RABBITMQ_HOST,
            self::RABBITMQ_PORT,
            self::RABBITMQ_USER,
            self::RABBITMQ_PASSWORD);

        return $connection->channel();
    }
}
