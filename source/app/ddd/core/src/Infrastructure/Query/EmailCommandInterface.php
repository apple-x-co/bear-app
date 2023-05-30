<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface EmailCommandInterface
{
    /**
     * @return array{id: int}
     */
    #[DbQuery('email_add', 'row')]
    public function add(
        string $senderEmailAddress,
        ?string $senderName,
        string $subject,
        string $text,
        ?string $html,
        DateTimeImmutable $scheduleAt,
        ?DateTimeImmutable $createdAt = null,
    ): array;

    #[DbQuery('email_sent')]
    public function sent(int $id, DateTimeImmutable $sentAt): void;
}
