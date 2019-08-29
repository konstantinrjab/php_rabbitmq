<?php

namespace App\Collection;

use App\Entity\Supplier;

class SupplierCollection extends ObjectCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct(Supplier::class, $elements);
    }
}
