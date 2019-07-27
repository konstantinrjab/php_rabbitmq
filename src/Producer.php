<?php

use PhpAmqpLib\Message\AMQPMessage;

class Producer
{
    public function produce()
    {
        $channel = RabbitmqConnection::getChannel();
        $channel->queue_declare('hello', false, false, false, false);

        $message = new AMQPMessage(uniqid());
        $channel->basic_publish($message, '', 'hello');

        echo " [x] Sent '$message->body'\n";
    }
}
