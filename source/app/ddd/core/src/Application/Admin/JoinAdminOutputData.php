<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

class JoinAdminOutputData
{
    public function __construct(
        public readonly string $redirectUrl,
    ) {
    }
}
