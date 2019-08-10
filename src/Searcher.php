<?php

namespace App;

use App\Entity\SearchRequest;
use App\Repository\RedisRepository;

class Searcher
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
        $this->logger->addInfo(sprintf('Searching, flowId: %s, supplier: %s', $searchRequest->getFlowId(), $searchRequest->getSupplier()));
        sleep(mt_rand(1, 3));
        $this->logger->addInfo(sprintf('Job done, flowId: %s, supplier: %s', $searchRequest->getFlowId(), $searchRequest->getSupplier()));
        $this->redisRepository->insert($searchRequest->getFlowId(), 'Hello world');
    }
}
