<?php

require_once 'autoloader.php';

$sender = new WorkerSender();
$sender->execute(123);

for ($workersCount = 1; $workersCount <= 3; $workersCount++) {
    exec('php worker_reciever.php > /dev/null &');
}
