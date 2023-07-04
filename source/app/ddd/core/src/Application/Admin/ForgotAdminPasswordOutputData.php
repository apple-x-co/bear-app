<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

class ForgotAdminPasswordOutputData
{
    public function __construct(
        public readonly string $redirectUrl,
    ) {
    }
}
