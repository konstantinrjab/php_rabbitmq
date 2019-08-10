<?php

use App\Entity\SearchRequest;
use App\AsyncSearch;

require_once '../vendor/autoload.php';

$sender = new AsyncSearch();

$searchRequest = new SearchRequest();
$searchRequest->setFlowId(uniqid());

//$sender->search($searchRequest, ['provider1']);
$sender->search($searchRequest, ['provider1', 'provider2']);
