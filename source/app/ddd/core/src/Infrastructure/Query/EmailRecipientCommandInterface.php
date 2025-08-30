<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface EmailRecipientCommandInterface
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    #[DbQuery('emails/email_recipient_add')]
    public function add(
        int $emailId,
        string $recipientType,
        string $recipientEmailAddress,
        string|null $recipientName = null,
        DateTimeImmutable|null $createdDate = null,
    ): void;
}
