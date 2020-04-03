<?php

namespace App\Controllers;

use App\Collections\SearchResultCollection;
use App\Collections\SupplierCollection;
use App\Requests\SearchRequest;
use App\Entities\Supplier;
use App\Services\AsyncSearchService;

class SearchController
{
    /** @var AsyncSearchService $asyncSearchService */
    private $asyncSearchService;

    public function __construct(AsyncSearchService $asyncSearchService)
    {
        $this->asyncSearchService = $asyncSearchService;
    }

    public function search(SearchRequest $request): SearchResultCollection
    {
        $supplierCollection = $this->getSupplierCollection($request->getSupplierNames());

        return $this->asyncSearchService->search($request, $supplierCollection);
    }

    private function getSupplierCollection(array $supplierNames): SupplierCollection
    {
        $supplierCollection = new SupplierCollection();
        foreach ($supplierNames as $supplierName) {
            $supplier = new Supplier($supplierName);
            $supplierCollection->add($supplier);
        }

        return $supplierCollection;
    }
}
