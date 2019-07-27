<?php

require_once __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(function ($class_name) {
    $file = __DIR__ . DIRECTORY_SEPARATOR . $class_name . '.php';
    if (file_exists($file)) include $file;
});
