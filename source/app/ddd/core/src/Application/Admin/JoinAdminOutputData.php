<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

readonly class JoinAdminOutputData
{
    public function __construct(
        public string $redirectUrl,
    ) {
    }
}
