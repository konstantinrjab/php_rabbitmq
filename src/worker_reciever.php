<?php

require_once 'autoloader.php';

$worker = new WorkerReceiver();

$worker->listen();
