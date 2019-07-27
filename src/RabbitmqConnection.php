<?php

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitmqConnection
{
    public static function getChannel(): AMQPChannel
    {
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');

        return $connection->channel();
    }
}
