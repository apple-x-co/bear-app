<?php

declare(strict_types=1);

namespace AppCore\Domain\Auth;

final readonly class AdminIdentity
{
    public function __construct(
        public int $id,
        public string $displayName,
    ) {
    }
}
