<?php

use App\WorkerReceiver;

require_once '../vendor/autoload.php';

$worker = new WorkerReceiver();

$worker->listen();
