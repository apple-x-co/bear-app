<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use SensitiveParameter;

class CreateAdminInputData
{
    public function __construct(
        public readonly string $username,
        public readonly string $displayName,
        #[SensitiveParameter] public readonly string $password,
        public readonly string $signature,
    ) {
    }
}
