<?php

declare(strict_types=1);

namespace AppCore\Domain\Mail;

interface AddressInterface
{
    public function getEmail(): string;

    public function getName(): ?string;
}
