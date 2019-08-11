<?php

namespace App;

use App\Entity\SearchRequest;
use App\Entity\SearchResult;
use App\Repository\RedisRepository;
use App\Service\RedisService;

class Searcher
{
    /** @var FileLogger $logger */
    private $logger;

    /** @var RedisRepository $redisRepository */
    private $redisRepository;

    /** @var RedisService $redisService */
    private $redisService;

    public function __construct(FileLogger $logger, RedisRepository $redisRepository, RedisService $redisService)
    {
        $this->logger = $logger;
        $this->redisRepository = $redisRepository;
        $this->redisService = $redisService;
    }

    public function search(SearchRequest $searchRequest): void
    {
        $supplierName = $searchRequest->getSupplier()->getName();
        $this->logger->logSearchStarted($searchRequest->getFlowId(), $supplierName);

        $searchResult = $this->createSearchResult($searchRequest);

        $sleepTime = mt_rand(3, 6);
        sleep($sleepTime);
        $this->redisRepository->insert($this->redisService->getSearchIdBySearchRequest($searchRequest), serialize($searchResult));
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
}
