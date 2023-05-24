<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Input\Admin;

use SensitiveParameter;

class SignUpInput
{
    /**
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public function __construct(
        public readonly string $username,
        public readonly string $displayName,
        #[SensitiveParameter] public readonly string $password,
        public readonly string $signature,
        public readonly string $__csrf_token, // phpcs:ignore
        public readonly ?string $continue,
    ) {
    }
}
