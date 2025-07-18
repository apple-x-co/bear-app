<?php

declare(strict_types=1);

namespace MyVendor\MyProject\InputQuery\User;

use Ray\InputQuery\Attribute\Input;
use SensitiveParameter;

readonly class LoginUserInput
{
    /**
     * @psalm-suppress UndefinedAttributeClass
     * @SuppressWarnings(PHPMD.CamelCaseParameterName)
     */
    public function __construct(
        #[Input] public string $username,
        #[Input]
        #[SensitiveParameter]
        public string $password,
        #[Input]
        public string $__csrf_token, // phpcs:ignore
        #[Input]
        public string|null $login,
    ) {
    }

    public function isValid(): bool
    {
        return $this->username !== '' && $this->password !== '';
    }

//    public function isSubmitted(): bool
//    {
//        return $this->login !== '';
//    }
}
