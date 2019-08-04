<?php

use App\WorkerReceiver;

require_once '../vendor/autoload.php';

$flowId = $argv[1];

$worker = new WorkerReceiver($flowId);

$worker->listen();
