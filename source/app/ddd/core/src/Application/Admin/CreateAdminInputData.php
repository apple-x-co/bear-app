<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use SensitiveParameter;

readonly class CreateAdminInputData
{
    public function __construct(
        public string $username,
        public string $displayName,
        #[SensitiveParameter]
        public string $password,
        public string $signature,
    ) {
    }
}
