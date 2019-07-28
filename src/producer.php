<?php

require_once 'autoloader.php';

for ($producersCount = 1; $producersCount <= 3; $producersCount++) {
    $producer = new Producer($producersCount);
    $producer->produce(uniqid());
    sleep(1);
}
