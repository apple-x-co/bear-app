<?php

declare(strict_types=1);

namespace AppCore\Domain\Throttle;

interface ThrottleRepositoryInterface
{
    public function findByThrottleKey(string $throttleKey): ?Throttle;

    public function store(Throttle $throttle): void;
}
