<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;

final class EmailQueueEntityFactory
{
    /**
     * @param positive-int     $id
     * @param positive-int     $active
     * @param non-negative-int $attempts
     * @param non-negative-int $maxAttempts
     *
     * @SuppressWarnings("PHPMD.ExcessiveParameterList")
     */
    public static function factory(
        int $id,
        string $senderEmailAddress,
        string|null $senderName,
        string $subject,
        string $text,
        string|null $html,
        string $priority,
        int $active,
        int $attempts,
        int $maxAttempts,
        string $scheduleDate,
        string|null $sentDate,
        string $createdDate,
    ): EmailQueueEntity {
        return new EmailQueueEntity(
            $id,
            $senderEmailAddress,
            $senderName,
            $subject,
            $text,
            $html,
            $priority,
            $active,
            $attempts,
            $maxAttempts,
            new DateTimeImmutable($scheduleDate),
            $sentDate === null ? null : new DateTimeImmutable($sentDate),
            new DateTimeImmutable($createdDate),
        );
    }
}
