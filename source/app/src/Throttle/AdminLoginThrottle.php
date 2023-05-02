<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Throttle;

use AppCore\Domain\Throttle\Throttle;
use AppCore\Domain\Throttle\ThrottleRepositoryInterface;
use AppCore\Infrastructure\Query\ThrottleRemoveByKeyInterface;
use DateInterval;
use DateTimeImmutable;
use Ray\Di\Di\Named;

/**
 * Admin認証スロットリング
 */
class AdminLoginThrottle implements LoginThrottleInterface
{
    public function __construct(
        #[Named('admin_auth_attempt_interval')] private readonly string $attemptInterval,
        #[Named('admin_auth_max_attempts')] private readonly int $maxAttempts,
        private readonly ThrottleRepositoryInterface $throttleRepository,
        private readonly ThrottleRemoveByKeyInterface $throttleRemoveByKey,
    ) {
    }

    public function isExceeded(string $username, ?string $remoteIp = null): bool
    {
        $throttle = $this->throttleRepository->findByThrottleKey($username);
        if ($throttle === null) {
            return false;
        }

        return $throttle->isExceeded();
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function countUp(string $username, string $remoteIp): void
    {
        $throttle = $this->throttleRepository->findByThrottleKey($username);
        if ($throttle === null) {
            $throttle = new Throttle(
                $username,
                $remoteIp,
                0,
                $this->maxAttempts,
                $this->attemptInterval,
                (new DateTimeImmutable())->add(DateInterval::createFromDateString($this->attemptInterval)),
            );
        }

        $this->throttleRepository->store($throttle->countUp($remoteIp));
    }

    public function clear(string $username): void
    {
        ($this->throttleRemoveByKey)($username);
    }
}
