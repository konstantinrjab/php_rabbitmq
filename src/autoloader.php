<?php

require_once __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(function (string $className): void {
    $file = __DIR__ . DIRECTORY_SEPARATOR . 'Classes'. DIRECTORY_SEPARATOR . $className . '.php';
    if (file_exists($file)) {
        include $file;
    };
});
