<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

readonly class ForgotAdminPasswordOutputData
{
    public function __construct(
        public string $redirectUrl,
    ) {
    }
}
