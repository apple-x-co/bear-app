<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

class CreateAdminEmailInputData
{
    public function __construct(
        public readonly int $adminId,
        public readonly string $emailAddress,
    ) {
    }
}
