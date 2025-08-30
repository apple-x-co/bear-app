<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use SensitiveParameter;

readonly class ResetAdminPasswordInputData
{
    public function __construct(
        #[SensitiveParameter]
        public string $password,
        public string $signature,
    ) {
    }
}
