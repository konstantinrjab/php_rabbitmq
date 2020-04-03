<?php

namespace App\Requests;

use App\Entities\Supplier;

class SupplierSearchRequest
{
    /** @var string $id */
    private $id;
    /** @var string $flowId */
    private $flowId;
    /** @var Supplier $supplier */
    private $supplier;

    public function __construct(string $flowId, Supplier $supplier)
    {
        $this->flowId = $flowId;
        $this->supplier = $supplier;
        $this->id =  $flowId . '_' . $supplier->getName();
    }

    public function getFlowId(): string
    {
        return $this->flowId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getSupplier(): Supplier
    {
        return $this->supplier;
    }
}
