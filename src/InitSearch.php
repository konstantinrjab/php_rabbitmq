<?php

namespace App;

use App\Collection\SearchResultCollection;
use App\Collection\SupplierCollection;
use App\Entity\SearchRequest;
use App\Entity\Supplier;

class InitSearch
{
    /** @var AsyncSearch $asyncSearch */
    private $asyncSearch;

    public function __construct(AsyncSearch $asyncSearch)
    {
        $this->asyncSearch = $asyncSearch;
    }

    public function search(array $supplierNames): SearchResultCollection
    {
        $supplierCollection = $this->createSupplierCollection($supplierNames);
        $searchRequest = $this->createSearchRequest();

        return $this->asyncSearch->search($searchRequest, $supplierCollection);
    }

    private function createSupplierCollection(array $supplierNames): SupplierCollection
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
