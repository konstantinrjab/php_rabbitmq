<?php

namespace App\Requests;

class SearchRequest
{
    /** @var string $flowId */
    private $flowId;
    /** @var array $supplierNames */
    private $supplierNames;

    public function __construct(array $supplierNames)
    {
        $this->flowId = uniqid();
        $this->supplierNames = $supplierNames;
    }

    public function getFlowId(): string
    {
        return $this->flowId;
    }

    public function getSupplierNames(): array
    {
        return $this->supplierNames;
    }
}
