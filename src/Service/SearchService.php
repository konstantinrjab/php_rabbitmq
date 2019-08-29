<?php

namespace App\Service;

use App\Entity\SearchRequest;
use App\Entity\SearchResult;
use App\FileLogger;
use App\Repository\RedisRepository;

class SearchService
{
    /** @var FileLogger $logger */
    private $logger;

    /** @var RedisRepository $redisRepository */
    private $redisRepository;

    public function __construct(FileLogger $logger, RedisRepository $redisRepository)
    {
        $this->logger = $logger;
        $this->redisRepository = $redisRepository;
    }

    public function search(SearchRequest $searchRequest): void
    {
        $supplierName = $searchRequest->getSupplier()->getName();
        $this->logger->logSearchStarted($searchRequest->getFlowId(), $supplierName);

        $searchResult = $this->createSearchResult($searchRequest);

        $sleepTime = $this->wait();

        $this->redisRepository->insertSearchResult($searchRequest, $searchResult);
        $this->logger->logJobIsDone($sleepTime, $searchRequest->getFlowId(), $supplierName);
    }

    private function createSearchResult(SearchRequest $searchRequest): SearchResult
    {
        $searchResult = new SearchResult();
        $searchResult->setFlowId($searchRequest->getFlowId());
        $searchResult->setSupplier($searchRequest->getSupplier());
        $searchResult->setData(uniqid('Result: ', true));

        return $searchResult;
    }

    private function wait(): int
    {
        $sleepTime = mt_rand(3, 6);
        sleep($sleepTime);

        return $sleepTime;
    }
}
