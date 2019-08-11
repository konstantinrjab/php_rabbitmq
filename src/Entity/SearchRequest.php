<?php

namespace App\Entity;

class SearchRequest
{
    /** @var string $flowId */
    private $flowId;

    /** @var Supplier $supplier */
    private $supplier;

    public function getFlowId(): string
    {
        return $this->flowId;
    }

    public function setFlowId(string $flowId): void
    {
        $this->flowId = $flowId;
    }

    public function getSupplier(): Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(Supplier $supplier): void
    {
        $this->supplier = $supplier;
    }
}
