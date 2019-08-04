<?php

namespace App;

use App\Entity\SearchRequest;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class AsyncSearch
{
    public function search(SearchRequest $searchRequest, array $suppliers): void
    {
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $flowId = $searchRequest->getFlowId();
        $channel->queue_declare(
            $flowId,    #queue - Queue names may be up to 255 bytes of UTF-8 characters
            false,              #passive - can use this to check whether an exchange exists without modifying the server state
            false,               #durable, make sure that RabbitMQ will never lose our queue if a crash occurs - the queue will survive a broker restart
            false,              #exclusive - used by only one connection and the queue will be deleted when that connection closes
            true               #auto delete - queue is deleted when last consumer unsubscribes
        );

        foreach ($suppliers as $supplier) {
            $searchRequest->setSupplier($supplier);
            $msg = new AMQPMessage(
                serialize($searchRequest),
                ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT] # make message persistent, so it is not lost if server crashes or quits
            );

            $channel->basic_publish(
                $msg,               #message
                '',                 #exchange
                $flowId     #routing key (queue)
            );
        }

        $channel->close();
        $connection->close();

        foreach ($suppliers as $supplier) {
            exec("php worker_reciever.php $flowId > /dev/null &");
        }
    }
}