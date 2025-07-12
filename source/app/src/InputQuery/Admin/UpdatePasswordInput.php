<?php

declare(strict_types=1);

namespace MyVendor\MyProject\InputQuery\Admin;

use Ray\InputQuery\Attribute\Input;
use SensitiveParameter;

readonly class UpdatePasswordInput
{
    /**
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public function __construct(
        #[Input]
        #[SensitiveParameter]
        public string $oldPassword,
        #[Input]
        #[SensitiveParameter]
        public string $password,
        #[Input]
        #[SensitiveParameter]
        #[Input] public string $passwordConfirmation,
        #[Input] public string $__csrf_token, // phpcs:ignore
        #[Input] public ?string $update,
    ) {
    }
}
