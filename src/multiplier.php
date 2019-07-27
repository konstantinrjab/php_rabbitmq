<?php

require_once 'autoloader.php';

$producer = new Producer();

for ($producersCount = 1; $producersCount < 20; $producersCount++) {
    $producer->produce();
    sleep(1);
}
