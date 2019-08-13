<?php

namespace App;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class FileLogger extends Logger
{
    private const PATH = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'main.log';
    private const JOB_DONE_TEMPLATE = 'Job done, took %ss, flowId: %s, supplier: %s';
    private const SEARCH_STARTED_TEMPLATE = 'Searching, flowId: %s, supplier: %s';
    private const SLEEP_TEMPLATE = 'Seep for %ss, searchId: %s';
    private const READY_STATE_TEMPLATE = 'Ready to handle incoming messages, searchId: %s';

    public function __construct()
    {
        parent::__construct('fileLogger');
        $this->pushHandler(new StreamHandler(self::PATH, Logger::DEBUG));
    }

    public function logJobIsDone(int $time, string $flowId, string $supplierName): void
    {
        $this->addInfo(sprintf(self::JOB_DONE_TEMPLATE, $time, $flowId, $supplierName));
    }

    public function logSearchStarted(string $flowId, string $supplierName): void
    {
        $this->addInfo(sprintf(self::SEARCH_STARTED_TEMPLATE, $flowId, $supplierName));
    }

    public function logSleep(string $searchId, int $time): void
    {
        $this->addInfo(sprintf(self::SLEEP_TEMPLATE, $searchId, $time));
    }
    public function logReadyState(string $searchId): void
    {
        $this->addInfo(sprintf(self::READY_STATE_TEMPLATE, $searchId));
    }
}
