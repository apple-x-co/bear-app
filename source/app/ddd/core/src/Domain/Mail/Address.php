<?php

declare(strict_types=1);

namespace AppCore\Domain\Mail;

use Override;

final class Address implements AddressInterface
{
    public function __construct(
        protected readonly string $email,
        protected readonly string|null $name = null,
    ) {
    }

    #[Override]
    public function getEmail(): string
    {
        return $this->email;
    }

    #[Override]
    public function getName(): string|null
    {
        return $this->name;
    }
}
