<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeInterface;
use Ray\IdentityValueModule\Uuid;
use Ray\MediaQuery\Annotation\DbQuery;

interface VerificationCodeCommandInterface
{
    /** @return array{uuid: string} */
    #[DbQuery('verification_codes/verification_code_add', 'row')]
    public function add(
        string $emailAddress,
        string $url,
        string $code,
        DateTimeInterface $expireDate,
        Uuid|null $uuid = null,
        DateTimeInterface|null $createdDate = null,
        DateTimeInterface|null $updatedDate = null,
    ): array;

    #[DbQuery('verification_codes/verification_code_verified')]
    public function verified(int $id, DateTimeInterface $verifiedDate): void;
}
