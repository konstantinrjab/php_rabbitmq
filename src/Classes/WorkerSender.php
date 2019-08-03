<?php

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class WorkerSender
{
    public function execute($invoiceNum)
    {
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare(
            'invoice_queue',    #queue - Queue names may be up to 255 bytes of UTF-8 characters
            false,              #passive - can use this to check whether an exchange exists without modifying the server state
            true,               #durable, make sure that RabbitMQ will never lose our queue if a crash occurs - the queue will survive a broker restart
            false,              #exclusive - used by only one connection and the queue will be deleted when that connection closes
            false               #auto delete - queue is deleted when last consumer unsubscribes
        );

        $msg = new AMQPMessage(
            $invoiceNum,
            ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT] # make message persistent, so it is not lost if server crashes or quits
        );

        $channel->basic_publish(
            $msg,               #message
            '',                 #exchange
            'invoice_queue'     #routing key (queue)
        );

        $channel->close();
        $connection->close();
    }
}