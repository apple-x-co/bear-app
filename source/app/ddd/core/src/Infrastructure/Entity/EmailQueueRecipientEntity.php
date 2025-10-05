<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Entity;

use DateTimeImmutable;

/**
 * パフォーマンスを優先したい場合（バッチ）はリポジトリを経由せずに使用する
 */
final readonly class EmailQueueRecipientEntity
{
    /**
     * @param positive-int $id
     * @param positive-int $emailQueueId
     *
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function __construct(
        public int $id,
        public int $emailQueueId,
        public string $recipientType,
        public string $recipientEmailAddress,
        public string|null $recipientName,
        public DateTimeImmutable $createdDate,
    ) {
    }
}
