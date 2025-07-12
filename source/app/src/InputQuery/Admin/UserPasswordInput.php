<?php

declare(strict_types=1);

namespace MyVendor\MyProject\InputQuery\Admin;

use Ray\InputQuery\Attribute\Input;
use SensitiveParameter;

class UserPasswordInput
{
    /**
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public function __construct(
        #[Input]
        #[SensitiveParameter]
        public readonly string $password,
        #[Input] public readonly string $__csrf_token, // phpcs:ignore
        #[Input] public readonly string|null $continue,
    ) {
    }
}
