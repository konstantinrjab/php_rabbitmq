<?php

require_once 'autoloader.php';

use PhpAmqpLib\Message\AMQPMessage;

$channel = RabbitmqConnection::getChannel();
$channel->queue_declare('hello', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function (AMQPMessage $message): void {
    echo " [x] Received '", $message->body, "'\n";
};

$channel->basic_consume('hello', '', false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}
