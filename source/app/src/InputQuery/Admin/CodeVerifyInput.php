<?php

declare(strict_types=1);

namespace MyVendor\MyProject\InputQuery\Admin;

use Ray\InputQuery\Attribute\Input;

readonly class CodeVerifyInput
{
    /**
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public function __construct(
        #[Input]
        public string $uuid,
        #[Input]
        public string $code,
        #[Input]
        public string $__csrf_token, // phpcs:ignore
        #[Input]
        public string|null $continue,
    ) {
    }
}
