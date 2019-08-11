<?php

use App\Entity\SearchRequest;
use App\AsyncSearch;
use App\Collection\SupplierCollection;

require_once '../vendor/autoload.php';

$asyncSearch = new AsyncSearch();
$searchRequest = new SearchRequest();
$searchRequest->setFlowId(uniqid());

$supplierNames = ['supplier1', 'supplier2', 'supplier3', 'supplier4', 'supplier5', 'supplier6', 'supplier7', 'supplier8'];
$supplierCollection = new SupplierCollection();
$supplierCollection->createAndAddSuppliers($supplierNames);

$searchResultCollection = $asyncSearch->search($searchRequest, $supplierCollection);

echo 'Get result from '.count($searchResultCollection).' suppliers out of '.count($supplierNames)."\n\n";
