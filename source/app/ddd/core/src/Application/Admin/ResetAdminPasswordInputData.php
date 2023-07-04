<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use SensitiveParameter;

class ResetAdminPasswordInputData
{
    public function __construct(
        #[SensitiveParameter] public readonly string $password,
        public readonly string $signature,
    ) {
    }
}
