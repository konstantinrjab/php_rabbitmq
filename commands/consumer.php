<?php

require_once '../vendor/autoload.php';

$consumer = new \App\Consumer();
$consumer->listen();
