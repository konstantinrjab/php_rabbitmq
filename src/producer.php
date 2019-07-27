<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

try {
    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
    $channel = $connection->channel();

    $channel->queue_declare('hello', false, false, false, false);

    $message = new AMQPMessage(uniqid());
    $channel->basic_publish($message, '', 'hello');

    echo " [x] Sent '$message->body'\n";
} catch (Throwable $throwable) {
    $t = 1;
}
