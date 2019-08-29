<?php

namespace App\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Exception;

abstract class ObjectCollection extends ArrayCollection
{
    /** @var string $className */
    private $className;

    public function __construct(string $className, array $elements = [])
    {
        $this->className = $className;

        foreach ($elements as $element) {
            $this->checkElementClass($element);
            $this->add($element);
        }
        parent::__construct($elements);
    }

    public function add($element): bool
    {
        $this->checkElementClass($element);

        return parent::add($element);
    }

    private function checkElementClass($element): void
    {
        if (!$element instanceof $this->className) {
            throw new Exception("Element must be instance of $this->className");
        }
    }
}
