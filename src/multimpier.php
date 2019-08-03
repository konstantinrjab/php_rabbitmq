<?php

require_once 'autoloader.php';

for ($producersCount = 1; $producersCount <= 10; $producersCount++) {
    exec('php task.php > /dev/null &');
}
