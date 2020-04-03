<?php

namespace App\Repositories;

use App\Database\RedisConnectionManger;
use App\Entities\SearchResult;
use App\Requests\SupplierSearchRequest;
use App\Entities\Supplier;

class RedisRepository
{
    private const TTL = 10;

    public function getSearchIdByFlowIdAndSupplier(string $flowId, Supplier $supplier): string
    {
        return $flowId . '_' . $supplier->getName();
    }

    public function getSearchResult(string $key): ?SearchResult
    {
        $connection = RedisConnectionManger::getConnection();
        $result = $connection->get($key);
        if (!$result) {
            return null;
        }

        return unserialize($result);
    }

    public function insertSearchResult(SupplierSearchRequest $request, SearchResult $searchResult): void
    {
        $connection = RedisConnectionManger::getConnection();

        $connection->set($request->getId(), serialize($searchResult), self::TTL);
    }
}
