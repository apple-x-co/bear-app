<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

final readonly class ForgotAdminPasswordInputData
{
    public function __construct(
        public string $emailAddress,
    ) {
    }
}
