<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;

final class EmailQueueRecipientEntityFactory
{
    /**
     * @param positive-int $id
     * @param positive-int $emailQueueId
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function factory(
        int $id,
        int $emailQueueId,
        string $recipientType,
        string $recipientEmailAddress,
        string|null $recipientName,
        string $createdDate,
    ): EmailQueueRecipientEntity {
        return new EmailQueueRecipientEntity(
            $id,
            $emailQueueId,
            $recipientType,
            $recipientEmailAddress,
            $recipientName,
            new DateTimeImmutable($createdDate),
        );
    }
}
