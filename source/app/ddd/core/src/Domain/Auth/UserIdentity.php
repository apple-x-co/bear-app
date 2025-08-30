<?php

declare(strict_types=1);

namespace AppCore\Domain\Auth;

readonly class UserIdentity
{
    public function __construct(
        public int $id,
        public string $displayName,
    ) {
    }
}
