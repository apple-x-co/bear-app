<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Input\Admin;

class MultipleDemo
{
    /**
     * @param array<string> $fruits
     * @param array<string> $languages
     * @param array<string> $programmingLanguages
     *
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public function __construct(
        public readonly string $primaryLanguage,
        public readonly string $__csrf_token, // phpcs:ignore
        public readonly ?string $submit,
        public readonly array $fruits = [],
        public readonly array $languages = [],
        public readonly array $programmingLanguages = [],
    ) {
    }
}
