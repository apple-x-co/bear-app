<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Input\Admin;

class CodeVerifyInput
{
    /**
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public function __construct(
        public readonly string $uuid,
        public readonly string $code,
        public readonly string $__csrf_token, // phpcs:ignore
        public readonly ?string $continue,
    ) {
    }
}
