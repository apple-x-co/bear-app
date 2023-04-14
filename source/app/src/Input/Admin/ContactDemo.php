<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Input\Admin;

class ContactDemo
{
    /**
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public function __construct(
        public readonly string $username,
        public readonly string $__csrf_token, // phpcs:ignore
        public readonly ?string $mode,
    ) {
    }
}
