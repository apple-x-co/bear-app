<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use DateTimeImmutable;
use Ray\MediaQuery\Annotation\DbQuery;

interface EmailRecipientCommandInterface
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    #[DbQuery('email_recipient_add')]
    public function add(
        int $emailId,
        string $recipientType,
        string $recipientEmailAddress,
        ?string $recipientName = null,
        ?DateTimeImmutable $createdAt = null,
    ): void;
}
