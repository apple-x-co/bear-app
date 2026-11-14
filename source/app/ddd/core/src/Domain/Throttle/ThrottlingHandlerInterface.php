<?php

declare(strict_types=1);

namespace AppCore\Domain\Throttle;

interface ThrottlingHandlerInterface
{
    /** 試行回数を 1 増やし、上限を超えたら true を返す */
    public function countUp(string $key, string $remoteIp, string $attemptInterval, int $maxAttempts): bool;

    public function clear(string $key): void;
}
