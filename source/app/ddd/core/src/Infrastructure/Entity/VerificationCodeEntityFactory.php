<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;

class VerificationCodeEntityFactory
{
    public static function factory(
        int $id,
        string $uuid,
        string $emailAddress,
        string $url,
        string $code,
        string $expireDate,
        string|null $verifiedDate,
        string $createdDate,
        string $updatedDate,
    ): VerificationCodeEntity {
        return new VerificationCodeEntity(
            $id,
            $uuid,
            $emailAddress,
            $url,
            $code,
            new DateTimeImmutable($expireDate),
            $verifiedDate === null ? null : new DateTimeImmutable($verifiedDate),
            new DateTimeImmutable($createdDate),
            new DateTimeImmutable($updatedDate),
        );
    }
}
