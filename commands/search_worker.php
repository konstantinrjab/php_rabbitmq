<?php

use App\SearchWorker;

require_once '../vendor/autoload.php';

$searchId = $argv[1];

$container = new DI\Container();
$searchWorker = $container->get(SearchWorker::class);

$searchWorker->listen($searchId);
