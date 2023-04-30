<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Throttle;

interface LoginThrottleInterface
{
    public function isExceeded(string $username): bool;

    public function countUp(string $username, string $remoteIp): void;

    public function clear(string $username): void;
}
