<?php

namespace App\Service;

use App\Entity\SearchRequest;

class RedisService
{
    public function getSearchIdBySearchRequest(SearchRequest $searchRequest): string
    {
        return $this->getSearchIdByFlowIdAndSupplier($searchRequest->getFlowId(), $searchRequest->getSupplier());
    }

    public function getSearchIdByFlowIdAndSupplier(string $flowId, string $supplier): string
    {
        return $flowId.'_'.$supplier;
    }
}
