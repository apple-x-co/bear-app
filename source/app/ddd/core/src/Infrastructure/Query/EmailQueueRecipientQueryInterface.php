<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use AppCore\Infrastructure\Entity\EmailQueueRecipientEntity;
use AppCore\Infrastructure\Entity\EmailQueueRecipientEntityFactory;
use Ray\MediaQuery\Annotation\DbQuery;

interface EmailQueueRecipientQueryInterface
{
    /**
     * @param list<positive-int> $emailQueueIds
     *
     * @return list<EmailQueueRecipientEntity>
     */
    #[DbQuery('email_queue_recipients/email_queue_recipient_list_by_email_queue_ids', factory: EmailQueueRecipientEntityFactory::class)]
    public function listByEmailQueueIds(array $emailQueueIds): array;
}
