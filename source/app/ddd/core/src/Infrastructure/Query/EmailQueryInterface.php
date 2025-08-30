<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use Ray\MediaQuery\Annotation\DbQuery;

interface EmailQueryInterface
{
    /** @return list<array{id: int, sender_email_address: string, sender_name: string|null, subject: string, text: string, html: string|null, schedule_at: string, sent_at: string|null, created_at: string}> */
    #[DbQuery('emails/email_list')]
    public function list(): array;
}
