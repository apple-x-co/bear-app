<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Input\Admin;

class FieldsetDemo
{
    /**
     * @param array<int, array{zip?: string|null, state?: string|null, city?: string|null, street?: string|null}> $addresses
     *
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public function __construct(
        public readonly string $agree,
        public readonly string $__csrf_token, // phpcs:ignore
        public readonly ?string $submit,
        public readonly array $addresses = [],
    ) {
    }
}
