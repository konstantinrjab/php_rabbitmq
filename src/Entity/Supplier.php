<?php

namespace App\Entity;

class Supplier
{
    /** @var string $name */
    private $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
