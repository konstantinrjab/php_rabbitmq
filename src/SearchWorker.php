<?php

namespace App;

use App\Repository\RedisRepository;
use App\Service\SearchService;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class SearchWorker
{
    private const RESPONSE_TIMEOUT = 5;
    private const WAITING_TIMEOUT = 1;

    /** @var int $timeStarted */
    private $timeStarted;

    /** @var FileLogger $logger */
    private $logger;

    /** @var SearchService $searchService */
    private $searchService;

    /** @var RedisRepository $redisRepository */
    private $redisRepository;

    /** @var RabbitmqConnectionManger $rabbitmqConnectionManager */
    private $rabbitmqConnectionManager;

    public function __construct(
        FileLogger $logger,
        RedisRepository $redisRepository,
        SearchService $searchService,
        RabbitmqConnectionManger $rabbitmqConnectionManager
    )
    {
        $this->logger = $logger;
        $this->redisRepository = $redisRepository;
        $this->searchService = $searchService;
        $this->rabbitmqConnectionManager = $rabbitmqConnectionManager;
    }

    public function listen(string $searchId): void
    {
        $this->timeStarted = microtime(true);

        $rabbitConnection = $this->rabbitmqConnectionManager->getConnection();
        $channel = $rabbitConnection->channel();

        $channel->queue_declare(
            $searchId,
            false,
            true,
            false,
            true
        );

        /**
         * don't dispatch a new message to a worker until it has processed and
         * acknowledged the previous one. Instead, it will dispatch it to the
         * next worker that is not still busy.
         */
        $channel->basic_qos(
            null,   #prefetch size - prefetch window size in octets, null meaning "no specific limit"
            1,      #prefetch count - prefetch window in terms of whole messages
            null    #global - global=null to mean that the QoS settings should apply per-consumer, global=true to mean that the QoS settings should apply per-channel
        );

        /**
         * indicate interest in consuming messages from a particular queue. When they do
         * so, we say that they register a consumer or, simply put, subscribe to a queue.
         * Each consumer (subscription) has an identifier called a consumer tag
         */
        $channel->basic_consume(
            $searchId,
            '',                     #consumer tag - Identifier for the consumer, valid within the current channel. just string
            false,                  #no local - TRUE: the server will not send messages to the connection that published them
            false,                  #no ack, false - acks turned on, true - off.  send a proper acknowledgment from the worker, once we're done with a task
            false,                  #exclusive - queues may only be accessed by the current connection
            false,                  #no wait - TRUE: the server will not respond to the method. The client should not wait for a reply method
            array($this, 'process')
        );

        $this->logger->logReadyState($searchId);
        while (count($channel->callbacks) && !$this->timeoutReached() && !$this->isWorkDone($searchId)) {
            $this->wait($channel);
            $this->logger->logSleep($searchId, self::WAITING_TIMEOUT);
        }

        $channel->close();
        $rabbitConnection->close();
    }

    public function process(AMQPMessage $msg): void
    {
        $searchRequest = unserialize($msg->body);
        $this->searchService->search($searchRequest);

        /**
         * If a consumer dies without sending an acknowledgement the AMQP broker
         * will redeliver it to another consumer or, if none are available at the
         * time, the broker will wait until at least one consumer is registered
         * for the same queue before attempting redelivery
         */
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }

    private function timeoutReached(): bool
    {
        $currentTime = microtime(true);
        if ($currentTime >= $this->timeStarted + self::RESPONSE_TIMEOUT) {
            return true;
        }

        return false;
    }

    private function isWorkDone(string $searchId): bool
    {
        return (bool)$this->redisRepository->getSearchResult($searchId);
    }

    private function wait(AMQPChannel $channel): void
    {
        $channel->wait(null, true);
        sleep(self::WAITING_TIMEOUT);
    }
}