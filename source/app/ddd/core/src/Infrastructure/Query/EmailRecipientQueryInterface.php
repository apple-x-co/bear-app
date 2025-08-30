<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface EmailRecipientQueryInterface
{
    /**
     * @param array<int> $emailIds
     *
     * @return array<array{id: int, email_id: int, recipient_type: string, recipient_email_address: string, recipient_name: string|null, created_at: string}>
     */
    #[DbQuery('emails/email_recipient_list')]
    public function list(array $emailIds): array;
}
