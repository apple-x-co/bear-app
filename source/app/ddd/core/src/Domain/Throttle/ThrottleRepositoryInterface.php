<?php

declare(strict_types=1);

namespace AppCore\Domain\Throttle;

use DateTimeImmutable;

interface ThrottleRepositoryInterface
{
    /** 試行回数を 1 増やし（期限切れなら 1 に戻し）、更新後の状態を返す。同時に呼ばれても回数が失われない */
    public function increment(
        string $throttleKey,
        string $remoteIp,
        int $maxAttempts,
        string $interval,
        DateTimeImmutable $expireDate,
    ): Throttle;
}
