<?php

use App\Controllers\SearchController;
use App\Requests\SearchRequest;

require_once '../vendor/autoload.php';

$container = new DI\Container();
/** @var SearchController $searchController */
$searchController = $container->get(SearchController::class);

$supplierNames = ['supplier1', 'supplier2', 'supplier3', 'supplier4', 'supplier5', 'supplier6', 'supplier7'];
$searchRequest = new SearchRequest($supplierNames);

$suppliersCount = count($supplierNames);

echo "Started search id: {$searchRequest->getFlowId()} for {$suppliersCount} suppliers...\n";

$searchResultCollection = $searchController->search($searchRequest);

echo "Received result from {$searchResultCollection->count()} suppliers out of $suppliersCount\n";
