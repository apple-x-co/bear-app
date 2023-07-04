<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use SensitiveParameter;

class UpdateAdminPasswordInputData
{
    public function __construct(
        public readonly int $adminId,
        public readonly string $userName,
        #[SensitiveParameter] public readonly string $password,
    ) {
    }
}
