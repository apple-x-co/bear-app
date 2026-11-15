<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use AppCore\Domain\Throttle\ThrottleRepositoryInterface;
use AppCore\Domain\Throttle\ThrottlingHandlerInterface;
use AppCore\Infrastructure\Query\ThrottleRemoveByKeyInterface;
use DateInterval;
use DateTimeImmutable;
use Override;

readonly class ThrottlingHandler implements ThrottlingHandlerInterface
{
    public function __construct(
        private ThrottleRepositoryInterface $throttleRepository,
        private ThrottleRemoveByKeyInterface $throttleRemoveByKey,
    ) {
    }

    /** @SuppressWarnings("PHPMD.StaticAccess") */
    public function countUp(string $key, string $remoteIp, string $attemptInterval, int $maxAttempts): bool
    {
        $throttle = $this->throttleRepository->increment(
            $key,
            $remoteIp,
            $maxAttempts,
            $attemptInterval,
            (new DateTimeImmutable())->add(DateInterval::createFromDateString($attemptInterval)),
        );

        return $throttle->isExceeded();
    }

    #[Override]
    public function clear(string $key): void
    {
        ($this->throttleRemoveByKey)($key);
    }
}
