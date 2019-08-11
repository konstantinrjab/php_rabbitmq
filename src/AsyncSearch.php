<?php

namespace App;

use App\Entity\SearchRequest;
use App\Service\RedisService;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Channel\AMQPChannel;

class AsyncSearch
{
    /** @var RedisService $redisService */
    private $redisService;

    public function __construct()
    {
        $this->redisService = new RedisService();
    }

    public function search(SearchRequest $searchRequest, array $suppliers): void
    {
        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        foreach ($suppliers as $supplier) {
            $searchId = $this->redisService->getSearchIdByFlowIdAndSupplier($searchRequest->getFlowId(), $supplier);
            $channel->queue_declare(
                $searchId,
                false,    #passive - can use this to check whether an exchange exists without modifying the server state
                true,
                false,   #exclusive - used by only one connection and the queue will be deleted when that connection closes
                true
            );
            $this->sendJobMessage($searchRequest, $supplier, $channel, $searchId);
            exec("php search_worker.php $searchId > /dev/null &");
        }

        $channel->close();
        $connection->close();
    }

    private function sendJobMessage(SearchRequest $searchRequest, $supplier, AMQPChannel $channel, string $flowId): void
    {
        $searchRequest->setSupplier($supplier);
        $amqpMessage = new AMQPMessage(
            serialize($searchRequest),
            ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );

        $channel->basic_publish(
            $amqpMessage,
            '',
            $flowId
        );
    }
}