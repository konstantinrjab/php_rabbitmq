<?php

use PhpAmqpLib\Message\AMQPMessage;

class Consumer
{
    public function consume()
    {
        $channel = RabbitmqConnection::getChannel();
        $channel->queue_declare('hello', false, false, false, false);

        echo " [*] Waiting for messages\n";

        $callback = function (AMQPMessage $message): void {
            echo " [x] Received '", $message->body, "'\n";
        };

        $channel->basic_consume('hello', '', false, true, false, false, $callback);

        while ($channel->is_consuming()) {
            $channel->wait();
        }
    }
}
