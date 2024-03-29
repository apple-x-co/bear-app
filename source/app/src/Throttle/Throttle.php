<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Throttle;

use AppCore\Domain\Throttle\Throttle as Model;
use AppCore\Domain\Throttle\ThrottleRepositoryInterface;
use AppCore\Infrastructure\Query\ThrottleRemoveByKeyInterface;
use DateInterval;
use DateTimeImmutable;

class Throttle implements ThrottleInterface
{
    public function __construct(
        private readonly ThrottleRepositoryInterface $throttleRepository,
        private readonly ThrottleRemoveByKeyInterface $throttleRemoveByKey,
    ) {
    }

    public function isExceeded(string $key): bool
    {
        $throttle = $this->throttleRepository->findByThrottleKey($key);
        if ($throttle === null) {
            return false;
        }

        return $throttle->isExceeded();
    }

    /** @SuppressWarnings(PHPMD.StaticAccess) */
    public function countUp(string $key, string $remoteIp, string $attemptInterval, int $maxAttempts): void
    {
        $throttle = $this->throttleRepository->findByThrottleKey($key);
        if ($throttle === null) {
            $throttle = new Model(
                $key,
                $remoteIp,
                0,
                $maxAttempts,
                $attemptInterval,
                (new DateTimeImmutable())->add(DateInterval::createFromDateString($attemptInterval)),
            );
        }

        $this->throttleRepository->store($throttle->countUp($remoteIp));
    }

    public function clear(string $key): void
    {
        ($this->throttleRemoveByKey)($key);
    }
}
