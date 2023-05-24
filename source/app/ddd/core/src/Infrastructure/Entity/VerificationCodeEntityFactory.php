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
        string $expireAt,
        ?string $verifiedAt,
        string $createdAt,
        string $updatedAt,
    ): VerificationCodeEntity {
        return new VerificationCodeEntity(
            $id,
            $uuid,
            $emailAddress,
            $url,
            $code,
            new DateTimeImmutable($expireAt),
            $verifiedAt === null ? null : new DateTimeImmutable($verifiedAt),
            new DateTimeImmutable($createdAt),
            new DateTimeImmutable($updatedAt),
        );
    }
}
