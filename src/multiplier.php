<?php

require_once 'autoloader.php';

for ($producersCount = 1; $producersCount < 20; $producersCount++) {
    $producer = new Producer();
    $producer->produce();
    sleep(1);
}
