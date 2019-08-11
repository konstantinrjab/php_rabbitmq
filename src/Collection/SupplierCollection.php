<?php

namespace App\Collection;

use App\Entity\Supplier;
use Doctrine\Common\Collections\ArrayCollection;

class SupplierCollection extends ArrayCollection
{
    public function createAndAddSuppliers(array $supplierNames): void
    {
        foreach ($supplierNames as $supplierName) {
            $supplier = new Supplier();
            $supplier->setName($supplierName);
            $this->add($supplier);
        }
    }
}
