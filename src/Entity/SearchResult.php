<?php

namespace App\Entity;

class SearchResult
{
    /** @var string $flowId */
    private $flowId;

    /** @var string $supplier */
    private $supplier;

    /** @var string $data */
    private $data;

    public function getFlowId(): string
    {
        return $this->flowId;
    }

    public function setFlowId(string $flowId): void
    {
        $this->flowId = $flowId;
    }

    public function getSupplier(): string
    {
        return $this->supplier;
    }

    public function setSupplier(Supplier $supplier): void
    {
        $this->supplier = $supplier;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function setData(string $data): void
    {
        $this->data = $data;
    }
}
