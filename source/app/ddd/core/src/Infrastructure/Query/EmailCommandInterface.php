<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface EmailCommandInterface
{
    /** @return array{id: positive-int} */
    #[DbQuery('emails/email_add', 'row')]
    public function add(
        string $senderEmailAddress,
        string|null $senderName,
        string $subject,
        string $text,
        string|null $html,
        DateTimeImmutable $scheduleDate,
        DateTimeImmutable|null $createdDate = null,
    ): array;

    #[DbQuery('emails/email_sent')]
    public function sent(int $id, DateTimeImmutable $sentDate): void;
}
