<?php

use App\Workers\SearchWorker;

require_once '../vendor/autoload.php';

$searchId = $argv[1];

$container = new DI\Container();
/** @var SearchWorker $searchWorker */
$searchWorker = $container->get(SearchWorker::class);

$searchWorker->listen($searchId);
