<?php

namespace App\Services;

use App\Entities\SearchResult;
use App\Requests\SupplierSearchRequest;
use App\Loggers\FileLogger;
use App\Repositories\RedisRepository;

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

    public function search(SupplierSearchRequest $request): void
    {
        $supplierName = $request->getSupplier()->getName();
        $this->logger->logSearchStarted($request->getFlowId(), $supplierName);

        $searchResult = $this->createSearchResult($request);

        $sleepTime = $this->wait();

        $this->redisRepository->insertSearchResult($request, $searchResult);
        $this->logger->logJobIsDone($sleepTime, $request->getFlowId(), $supplierName);
    }

    private function createSearchResult(SupplierSearchRequest $request): SearchResult
    {
        $searchResult = new SearchResult();
        $searchResult->setFlowId($request->getFlowId());
        $searchResult->setSupplier($request->getSupplier());
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
