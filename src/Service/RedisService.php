<?php

namespace App\Service;

use App\Entity\SearchRequest;
use App\Entity\Supplier;

class RedisService
{
    public function getSearchIdBySearchRequest(SearchRequest $searchRequest): string
    {
        return $this->getSearchIdByFlowIdAndSupplier($searchRequest->getFlowId(), $searchRequest->getSupplier());
    }

    public function getSearchIdByFlowIdAndSupplier(string $flowId, Supplier $supplier): string
    {
        return $flowId.'_'.$supplier->getName();
    }
}
