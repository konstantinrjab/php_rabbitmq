<?php

namespace App;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class FileLogger extends Logger
{
    private const PATH = __DIR__.DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'main.log';

    public function __construct()
    {
        parent::__construct('fileLogger');
        $this->pushHandler(new StreamHandler(self::PATH, Logger::DEBUG));
    }
}
