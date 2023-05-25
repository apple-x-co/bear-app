<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Throttle;

interface ThrottleInterface
{
    public function isExceeded(string $key): bool;

    public function countUp(string $key, string $remoteIp, string $attemptInterval, int $maxAttempts): void;

    public function clear(string $key): void;
}
