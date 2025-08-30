<?php

declare(strict_types=1);

namespace AppCore\Domain\Throttle;

interface ThrottlingHandlerInterface
{
    public function isExceeded(string $key): bool;

    public function countUp(string $key, string $remoteIp, string $attemptInterval, int $maxAttempts): void;

    public function clear(string $key): void;
}
