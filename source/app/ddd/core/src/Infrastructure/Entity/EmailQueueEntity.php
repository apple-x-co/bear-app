<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;

final readonly class EmailQueueEntity
{
    /**
     * @param positive-int     $id
     * @param positive-int     $active
     * @param non-negative-int $attempts
     * @param non-negative-int $maxAttempts
     *
     * @SuppressWarnings("PHPMD.ExcessiveParameterList")
     */
    public function __construct(
        public int $id,
        public string $senderMailAddress,
        public string|null $senderName,
        public string $subject,
        public string $text,
        public string|null $html,
        public int $active,
        public int $attempts,
        public int $maxAttempts,
        public DateTimeImmutable $scheduleDate,
        public DateTimeImmutable|null $sentDate,
        public DateTimeImmutable $createdDate,
    ) {
    }
}
