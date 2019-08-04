<?php

use App\WorkerSender;

require_once '../vendor/autoload.php';

$sender = new WorkerSender();
$sender->execute(123);

//for ($workersCount = 1; $workersCount <= 1; $workersCount++) {
    exec('php worker_reciever.php > /dev/null &');
//}
