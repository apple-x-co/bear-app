<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Input\Admin;

use SensitiveParameter;

class UpdatePassword
{
    /**
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public function __construct(
        #[SensitiveParameter] public readonly string $oldPassword,
        #[SensitiveParameter] public readonly string $password,
        #[SensitiveParameter] public readonly string $passwordConfirmation,
        public readonly string $__csrf_token, // phpcs:ignore
        public readonly ?string $update,
    ) {
    }
}
