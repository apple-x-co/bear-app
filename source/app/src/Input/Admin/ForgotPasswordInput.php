<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Input\Admin;

class ForgotPasswordInput
{
    /**
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public function __construct(
        public readonly string $emailAddress,
        public readonly string $__csrf_token, // phpcs:ignore
        public readonly ?string $continue,
    ) {
    }
}
