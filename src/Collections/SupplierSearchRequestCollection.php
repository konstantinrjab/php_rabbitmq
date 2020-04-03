<?php

namespace App\Collections;

use App\Requests\SupplierSearchRequest;

/**
 * @method SupplierSearchRequest getIterator()
 * @method SupplierSearchRequest get($key)
 * @method SupplierSearchRequest first()
 * @method add(SupplierSearchRequest $element): bool
 */
class SupplierSearchRequestCollection extends ObjectCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct(SupplierSearchRequest::class, $elements);
    }
}
