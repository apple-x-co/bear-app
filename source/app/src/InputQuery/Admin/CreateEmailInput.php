<?php

declare(strict_types=1);

namespace MyVendor\MyProject\InputQuery\Admin;

use Ray\InputQuery\Attribute\Input;

readonly class CreateEmailInput
{
    /**
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public function __construct(
        #[Input]
        public string $emailAddress,
        #[Input]
        public string $__csrf_token, // phpcs:ignore
        #[Input]
        public string|null $create,
    ) {
    }
}
