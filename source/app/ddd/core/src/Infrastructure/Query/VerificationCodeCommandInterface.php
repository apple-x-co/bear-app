<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\IdentityValueModule\Uuid;
use Ray\MediaQuery\Annotation\DbQuery;

interface VerificationCodeCommandInterface
{
    /**
     * @return array{uuid: string}
     */
    #[DbQuery('verification_code_add', 'row')]
    public function add(
        string $emailAddress,
        string $url,
        string $code,
        DateTimeImmutable $expireAt,
        ?Uuid $uuid = null,
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null,
    ): array;

    #[DbQuery('verification_code_verified')]
    public function verified(int $id, DateTimeImmutable $verifiedAt): void;
}
