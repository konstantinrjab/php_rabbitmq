<?php

use App\InitSearch;

require_once '../vendor/autoload.php';

$container = new DI\Container();
$initSearch = $container->get(InitSearch::class);

$supplierNames = ['supplier1', 'supplier2', 'supplier3', 'supplier4', 'supplier5', 'supplier6', 'supplier7'];

$searchResultCollection = $initSearch->search($supplierNames);

echo 'Received result from '.count($searchResultCollection).' suppliers out of '.count($supplierNames)."\n\n";
