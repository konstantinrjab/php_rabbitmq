<?php

namespace App\Repository;

use App\Entity\SearchRequest;
use App\Entity\SearchResult;
use App\Entity\Supplier;
use Redis;

class RedisRepository
{
    private const TTL = 10;

    /** @var Redis $redis */
    private $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
        $this->redis->pconnect('redis');
    }

    public function getSearchIdByFlowIdAndSupplier(string $flowId, Supplier $supplier): string
    {
        return $flowId.'_'.$supplier->getName();
    }

    public function getSearchResult(string $key): ?SearchResult
    {
        $result = $this->redis->get($key);
        if (!$result) {
            return null;
        }

        return unserialize($result);
    }

    public function insertSearchResult(SearchRequest $searchRequest, SearchResult $searchResult): void
    {
        $key = $this->getSearchIdByFlowIdAndSupplier($searchRequest->getFlowId(), $searchRequest->getSupplier());

        $this->redis->set($key, serialize($searchResult), self::TTL);
    }
}
