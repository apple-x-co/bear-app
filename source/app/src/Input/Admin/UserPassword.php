<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Input\Admin;

use SensitiveParameter;

class UserPassword
{
    /**
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public function __construct(
        #[SensitiveParameter] public readonly string $password,
        public readonly string $__csrf_token, // phpcs:ignore
        public readonly ?string $continue,
    ) {
    }
}
