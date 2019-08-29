<?php

namespace App\Collection;

use App\Entity\SearchResult;

class SearchResultCollection extends ObjectCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct(SearchResult::class, $elements);
    }
}
