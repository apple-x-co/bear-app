<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Input\Admin;

use SensitiveParameter;

class ResetPasswordInput
{
    /**
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public function __construct(
        #[SensitiveParameter] public readonly string $password,
        public readonly string $signature,
        public readonly string $__csrf_token, // phpcs:ignore
        public readonly ?string $continue,
    ) {
    }
}
