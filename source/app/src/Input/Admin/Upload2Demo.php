<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Input\Admin;

class Upload2Demo
{
    /**
     * @param array{file: array{name: string, type: string, tmp_name: string, error: int, size: int}|null, clientFileName: string|null, clientMediaType: string|null, size: string|null, tmpName: string|null} $fileset
     *
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public function __construct(
        public readonly array $fileset,
        public readonly string $__csrf_token, // phpcs:ignore
        public readonly ?string $mode,
    ) {
    }
}
