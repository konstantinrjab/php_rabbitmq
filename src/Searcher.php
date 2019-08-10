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
        $this->logger->addInfo(sprintf('Searching, flowId: %s, supplier: %s', $searchRequest->getFlowId(), $searchRequest->getSupplier()));
        $searchResult = new SearchResult();
        $searchResult->setFlowId($searchRequest->getFlowId());
        $searchResult->setSupplier($searchRequest->getSupplier());
        $searchResult->setData(uniqid('Result: ', true));
        sleep(mt_rand(2, 5));
        $this->redisRepository->insert($this->redisService->getSearchIdBySearchRequest($searchRequest), serialize($searchResult));
        $this->logger->addInfo(sprintf('Job done, flowId: %s, supplier: %s', $searchRequest->getFlowId(), $searchRequest->getSupplier()));
    }
}
