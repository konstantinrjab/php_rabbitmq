<?php

namespace App;

use App\Collection\SearchResultCollection;
use App\Collection\SupplierCollection;
use App\Entity\SearchRequest;
use App\Entity\Supplier;
use App\Service\AsyncSearchService;

class InitSearch
{
    /** @var AsyncSearchService $asyncSearchService */
    private $asyncSearchService;

    public function __construct(AsyncSearchService $asyncSearchService)
    {
        $this->asyncSearchService = $asyncSearchService;
    }

    public function search(array $supplierNames): SearchResultCollection
    {
        $supplierCollection = $this->createAndFillSupplierCollection($supplierNames);
        $searchRequest = $this->createSearchRequest();

        return $this->asyncSearchService->search($searchRequest, $supplierCollection);
    }

    private function createAndFillSupplierCollection(array $supplierNames): SupplierCollection
    {
        $supplierCollection = new SupplierCollection();
        foreach ($supplierNames as $supplierName) {
            $supplier = new Supplier();
            $supplier->setName($supplierName);
            $supplierCollection->add($supplier);
        }

        return $supplierCollection;
    }

    private function createSearchRequest(): SearchRequest
    {
        $searchRequest = new SearchRequest();
        $searchRequest->setFlowId(uniqid());

        return $searchRequest;
    }
}
