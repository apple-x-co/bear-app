<?php

declare(strict_types=1);

namespace AppCore\Domain\Mail;

class Address implements AddressInterface
{
    public function __construct(
        protected readonly string $email,
        protected readonly ?string $name = null,
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
