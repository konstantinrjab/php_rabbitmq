<?php

namespace App;

use App\Collection\SearchResultCollection;
use App\Collection\SupplierCollection;
use App\Entity\SearchRequest;
use App\Helper\RabbitmqConnectionHelper;
use App\Repository\RedisRepository;
use App\Service\RedisService;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Channel\AMQPChannel;

class AsyncSearch
{
    private const SEARCH_TIMEOUT = 5;

    /** @var RedisService $redisService */
    private $redisService;

    /** @var RedisRepository $redisRepository */
    private $redisRepository;

    public function __construct(RedisService $redisService, RedisRepository $redisRepository)
    {
        $this->redisService = $redisService;
        $this->redisRepository = $redisRepository;
    }

    public function search(
        SearchRequest $searchRequest,
        SupplierCollection $supplierCollection
    ): SearchResultCollection
    {
        $connection = RabbitmqConnectionHelper::getConnection();
        $channel = $connection->channel();

        foreach ($supplierCollection as $supplier) {
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

        $this->waitForResults();

        return $this->assembleSearchResult($searchRequest->getFlowId(), $supplierCollection);
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

    private function assembleSearchResult(string $flowId, SupplierCollection $supplierCollection): SearchResultCollection
    {
        $searchResultCollection = new SearchResultCollection();
        foreach ($supplierCollection as $supplier) {
            $searchId = $this->redisService->getSearchIdByFlowIdAndSupplier($flowId, $supplier);
            $searchResult = $this->redisRepository->getSearchResult($searchId);
            if ($searchResult) {
                $searchResultCollection->add($searchResult);
            }
        }

        return $searchResultCollection;
    }

    private function waitForResults(): void
    {
        sleep(self::SEARCH_TIMEOUT);
    }
}