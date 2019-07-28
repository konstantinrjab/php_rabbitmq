<?php

use PhpAmqpLib\Message\AMQPMessage;

class Producer
{
    private const CHANNEL_NAME = 'hello';

    /** @var AMQPChannel $channel */
    private $channel;

    /** @var int $id */
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
        $channel = RabbitmqConnection::getChannel();
        $channel->queue_declare(self::CHANNEL_NAME, false, false, false, false);
        $this->channel = $channel;
    }

    public function produce(string $body): void
    {
        $message = new AMQPMessage($this->createMessageBody($body));
        $this->channel->basic_publish($message, '', self::CHANNEL_NAME);

        echo " [x] Sent '$message->body'\n";
    }

    private function createMessageBody(string $body): string
    {
        return 'Producer ' . $this->id . ': ' . $body;
    }
}
