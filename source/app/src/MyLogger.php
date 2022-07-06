<?php

declare(strict_types=1);

namespace MyVendor\MyProject;

use BEAR\AppMeta\AbstractAppMeta;

use function error_log;

class MyLogger implements MyLoggerInterface
{
    /** @var string */
    private $logFile;

    public function __construct(AbstractAppMeta $meta)
    {
        $this->logFile = $meta->logDir . '/bear-app.log';
    }

    public function log(string $message): void
    {
        error_log($message . PHP_EOL, 3, $this->logFile);
    }
}
