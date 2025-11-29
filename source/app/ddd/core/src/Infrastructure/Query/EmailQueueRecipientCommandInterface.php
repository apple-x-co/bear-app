<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeInterface;
use Ray\MediaQuery\Annotation\DbQuery;

interface EmailQueueRecipientCommandInterface
{
    /**
     * @param positive-int $emailQueueId
     *
     * @SuppressWarnings("PHPMD.LongVariable")
     */
    #[DbQuery('email_queue_recipients/email_queue_recipient_add')]
    public function add(
        int $emailQueueId,
        string $recipientType,
        string $recipientEmailAddress,
        string|null $recipientName = null,
        DateTimeInterface|null $createdDate = null,
    ): void;

    #[DbQuery('email_queue_recipients/email_queue_recipient_delete_sent_older', 'row')]
    public function deleteBySentOlder(DateTimeInterface $sentDate): void;
}
