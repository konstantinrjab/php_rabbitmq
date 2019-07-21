<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

try {
    $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
    $channel = $connection->channel();

    $channel->queue_declare('hello', false, false, false, false);

    $msg = new AMQPMessage('Hello World!');
    $channel->basic_publish($msg, '', 'hello');

    echo " [x] Sent 'Hello World!'\n";
} catch (Throwable $throwable) {
    $t = 1;
}
