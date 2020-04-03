<?php

namespace App\Services;

use App\Collections\SearchResultCollection;
use App\Collections\SupplierCollection;
use App\Collections\SupplierSearchRequestCollection;
use App\Requests\SearchRequest;
use App\Requests\SupplierSearchRequest;
use App\Database\RabbitmqConnectionManger;
use App\Repositories\RedisRepository;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Channel\AMQPChannel;

class AsyncSearchService
{
    private const SEARCH_TIMEOUT = 5;

    /** @var RedisRepository $redisRepository */
    private $redisRepository;

    public function __construct(
        RedisRepository $redisRepository
    ) {
        $this->redisRepository = $redisRepository;
    }

    public function search(
        SearchRequest $searchRequest,
        SupplierCollection $supplierCollection
    ): SearchResultCollection {
        $rabbitmqConnection = RabbitmqConnectionManger::getConnection();
        $channel = $rabbitmqConnection->channel();

        $requestCollection = new SupplierSearchRequestCollection();

        foreach ($supplierCollection as $supplier) {
            $searchSupplierRequest = new SupplierSearchRequest($searchRequest->getFlowId(), $supplier);
            $requestCollection->add($searchSupplierRequest);

            $channel->queue_declare(
                $searchSupplierRequest->getId(),
                false,    #passive - can use this to check whether an exchange exists without modifying the server state
                true,
                false,   #exclusive - used by only one rabbitmqConnection and the queue will be deleted when that rabbitmqConnection closes
                true
            );
            $this->sendJobMessage($searchSupplierRequest, $channel);
            exec("php search_worker.php {$searchSupplierRequest->getId()} > /dev/null &");
        }

        $channel->close();
        $rabbitmqConnection->close();

        $this->waitForResults();

        return $this->assembleSearchResult($requestCollection);
    }

    private function sendJobMessage(SupplierSearchRequest $searchSupplierRequest, AMQPChannel $channel): void
    {
        $amqpMessage = new AMQPMessage(
            serialize($searchSupplierRequest),
            ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );

        $channel->basic_publish(
            $amqpMessage,
            '',
            $searchSupplierRequest->getId()
        );
    }

    private function assembleSearchResult(SupplierSearchRequestCollection $requestCollection): SearchResultCollection
    {
        $searchResultCollection = new SearchResultCollection();
        foreach ($requestCollection as $request) {
            $searchResult = $this->redisRepository->getSearchResult($request->getId());
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
