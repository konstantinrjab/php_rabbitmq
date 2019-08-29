<?php

namespace App\Collection;

use App\Entity\SearchResult;

/**
 * @method SearchResult get($key)
 * @method SearchResult first()
 * @method add(SearchResult $element): bool
 */
class SearchResultCollection extends ObjectCollection
{
    public function __construct(array $elements = [])
    {
        parent::__construct(SearchResult::class, $elements);
    }
}
