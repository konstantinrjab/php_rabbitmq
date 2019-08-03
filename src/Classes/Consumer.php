<?php

use PhpAmqpLib\Message\AMQPMessage;

class Consumer
{
    private const TIMEOUT = 10;

    /** @var int $timeStarted */
    private $timeStarted;

    public function listen()
    {
        $this->timeStarted = microtime(true);

        $channel = RabbitmqConnection::getChannel();
        $channel->queue_declare('hello', false, false, false, false);

        echo " [*] Waiting for messages\n";

        $callback = function (AMQPMessage $message): void {
            echo " [x] Received '", $message->body, "'\n";
        };

        $channel->basic_consume('hello', '', false, true, false, false, $callback);

        while ($channel->is_consuming() && $this->timeoutReached()) {
            $channel->wait();
        }
    }

    private function timeoutReached(): bool
    {
        //TODO: make it readable
        if (microtime(true) >= $this->timeStarted + self::TIMEOUT*1000) {
            return true;
        }
        sleep(2);

        return false;
    }
}
