<?php

namespace App;

use App\Entity\SearchRequest;
use Monolog\Logger;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class WorkerReceiver
{
    private const RESPONSE_TIMEOUT = 10;
    private const WAITING_TIMEOUT = 1;

    /** @var int $timeStarted */
    private $timeStarted;

    /** @var bool $isWaiting */
    private $isWaiting = false;

    /** @var bool $isWorkDone */
    private $isWorkDone = false;

    /** @var FileLogger $logger */
    private $logger;

    /** @var string $flowId */
    private $flowId;

    public function __construct(string $flowId)
    {
        $this->flowId = $flowId;
        $this->logger = new FileLogger();
    }

    public function listen(): void
    {
        $this->timeStarted = microtime(true);

        $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');

        $channel = $connection->channel();

        $channel->queue_declare(
            $this->flowId,    #queue
            false,              #passive
            false,               #durable, make sure that RabbitMQ will never lose our queue if a crash occurs
            false,              #exclusive - queues may only be accessed by the current connection
            true               #auto delete - the queue is deleted when all consumers have finished using it
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
            $this->flowId,        #queue
            '',                     #consumer tag - Identifier for the consumer, valid within the current channel. just string
            false,                  #no local - TRUE: the server will not send messages to the connection that published them
            false,                  #no ack, false - acks turned on, true - off.  send a proper acknowledgment from the worker, once we're done with a task
            false,                  #exclusive - queues may only be accessed by the current connection
            false,                  #no wait - TRUE: the server will not respond to the method. The client should not wait for a reply method
            array($this, 'process') #callback
        );

        while (count($channel->callbacks) && !$this->timeoutReached() && !$this->isWorkDone) {
            $this->wait();
            $channel->wait(null, true);
        }

        $channel->close();
        $connection->close();
    }

    public function process(AMQPMessage $msg): void
    {
        $searchRequest = unserialize($msg->body);
        $this->search($searchRequest);

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
            $this->logger->addInfo('Timeout reached, flowId: '.$this->flowId);

            return true;
        }

        return false;
    }

    private function search(SearchRequest $searchRequest): void
    {
        $this->logger->addInfo(sprintf('Searching, flowId: %s, supplier: %s', $searchRequest->getFlowId(), $searchRequest->getSupplier()));
        sleep(mt_rand(2, 5));
        $this->isWorkDone = true;
        $this->logger->addInfo(sprintf('Job done, flowId: %s, supplier: %s', $searchRequest->getFlowId(), $searchRequest->getSupplier()));
    }

    public function wait(): void
    {
        if (!$this->isWaiting) {
            $this->isWaiting = true;
            $this->logger->addInfo('Waiting for incoming messages, flowId: '.$this->flowId);
        }
        sleep(self::WAITING_TIMEOUT);
        $this->logger->addInfo('Slept, flowId: '.$this->flowId);
    }
}