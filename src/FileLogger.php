<?php

namespace App;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class FileLogger extends Logger
{
    private const PATH = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'main.log';
    private const JOB_DONE_TEMPLATE = 'Job done, took %ss, flowId: %s, supplier: %s';
    private const SEARCH_STARTED = 'Searching, flowId: %s, supplier: %s';

    public function __construct()
    {
        parent::__construct('fileLogger');
        $this->pushHandler(new StreamHandler(self::PATH, Logger::DEBUG));
    }

    public function logJobIsDone(int $sleepTime, string $flowId, string $supplierName): void
    {
        $this->addInfo(sprintf(self::JOB_DONE_TEMPLATE, $sleepTime, $flowId, $supplierName));
    }

    public function logSearchStarted(string $flowId, string $supplierName): void
    {
        $this->addInfo(sprintf(self::SEARCH_STARTED, $flowId, $supplierName));
    }
}
