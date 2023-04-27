<?php

declare(strict_types=1);

namespace AppCore\Domain;

interface EncrypterInterface
{
    public function encrypt(string $text): string;

    public function decrypt(string $payload): string;
}
