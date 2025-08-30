<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\LoggerInterface;
use BEAR\AppMeta\AbstractAppMeta;
use DateTimeInterface;

use function error_log;

use const PHP_EOL;

readonly class UserLogger implements LoggerInterface
{
    public function __construct(
        private AbstractAppMeta $appMeta,
        private DateTimeInterface $now,
    ) {
    }

    public function log(string $message): void
    {
        error_log(
            $this->now->format('Y-m-d H:i:s') . ' ' . $message . PHP_EOL,
            3,
            $this->appMeta->logDir . '/user.log',
        );
    }
}
