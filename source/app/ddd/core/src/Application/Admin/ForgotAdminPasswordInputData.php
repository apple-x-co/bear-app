<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

class ForgotAdminPasswordInputData
{
    public function __construct(
        public readonly string $emailAddress,
    ) {
    }
}
