<?php

use App\SearchWorker;

require_once '../vendor/autoload.php';

$searchId = $argv[1];

$worker = new SearchWorker($searchId);

$worker->listen();
