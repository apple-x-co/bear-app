<?php

declare(strict_types=1);

namespace AppCore\Domain\Hasher;

use Override;

use function password_hash;
use function password_verify;

use const PASSWORD_BCRYPT;

class PasswordHasher implements PasswordHasherInterface
{
    #[Override]
    public function hashType(): string
    {
        return PASSWORD_BCRYPT;
    }

    #[Override]
    public function hash(string $text): string
    {
        return password_hash($text, PASSWORD_BCRYPT);
    }

    #[Override]
    public function verify(string $password, string $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }
}
