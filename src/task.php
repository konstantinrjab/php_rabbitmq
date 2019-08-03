<?php

require_once 'autoloader.php';

$producer = new Producer(1);
sleep(3);
$producer->produce(uniqid());
