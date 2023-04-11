<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Input\Admin;

class UploadDemo
{
    /**
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public function __construct(
        public readonly ?string $submit,
    ) {
    }
}
