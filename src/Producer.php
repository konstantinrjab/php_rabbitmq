<?php

use PhpAmqpLib\Message\AMQPMessage;

class Producer
{
    const CHANNEL_NAME = 'hello';

    /** @var AMQPChannel $channel */
    private $channel;

    public function __construct()
    {
        $channel = RabbitmqConnection::getChannel();
        $channel->queue_declare(self::CHANNEL_NAME, false, false, false, false);
        $this->channel = $channel;
    }

    public function produce()
    {
        $message = new AMQPMessage(uniqid());
        $this->channel->basic_publish($message, '', self::CHANNEL_NAME);

        echo " [x] Sent '$message->body'\n";
    }
}
