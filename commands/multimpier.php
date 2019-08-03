<?php

require_once '../vendor/autoload.php';

for ($producersCount = 1; $producersCount <= 10; $producersCount++) {
    exec('php task.php > /dev/null &');
}
