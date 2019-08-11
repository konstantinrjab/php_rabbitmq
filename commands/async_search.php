<?php

use App\Entity\SearchRequest;
use App\AsyncSearch;
use App\Collection\SupplierCollection;

require_once '../vendor/autoload.php';

$asyncSearch = new AsyncSearch();
$searchRequest = new SearchRequest();
$searchRequest->setFlowId(uniqid());

$supplierCollection = new SupplierCollection();
$supplierCollection->createAndAddSuppliers(['supplier1', 'supplier2', 'supplier3', 'supplier4']);

$searchResult = $asyncSearch->search($searchRequest, $supplierCollection);
