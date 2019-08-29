<?php

namespace App\Collection;

use App\Entity\Supplier;

/**
 * @method Supplier get($key)
 * @method Supplier first()
 * @method add(Supplier $element): bool
 */
class SupplierCollection extends ObjectCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct(Supplier::class, $elements);
    }
}
