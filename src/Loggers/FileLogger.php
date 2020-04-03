<?php

namespace App\Loggers;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class FileLogger extends Logger
{
    private const JOB_DONE_TEMPLATE = 'Job done, took %ss, flowId: %s, supplier: %s';
    private const SEARCH_STARTED_TEMPLATE = 'Searching, flowId: %s, supplier: %s';
    private const SLEEP_TEMPLATE = 'Sleep for %ss, searchId: %s';
    private const READY_STATE_TEMPLATE = 'Ready to handle incoming messages, searchId: %s';
    private const LOGGER_NAME = 'fileLogger';

    public function __construct()
    {
        parent::__construct(self::LOGGER_NAME);
        $path = __DIR__  . '/../logs/main.log';
        $this->pushHandler(new StreamHandler($path, Logger::DEBUG));
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
