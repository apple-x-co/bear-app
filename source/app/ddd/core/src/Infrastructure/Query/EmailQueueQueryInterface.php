<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Query;

use AppCore\Infrastructure\Entity\EmailQueueEntity;
use AppCore\Infrastructure\Entity\EmailQueueEntityFactory;
use DateTimeInterface;
use Ray\MediaQuery\Annotation\DbQuery;
use Ray\MediaQuery\Annotation\Pager;
use Ray\MediaQuery\PagesInterface;

/** パフォーマンスを優先したい場合（バッチ）はリポジトリを経由せずに使用する */
interface EmailQueueQueryInterface
{
    /** @return list<EmailQueueEntity> */
    #[DbQuery('email_queues/email_queue_list', factory: EmailQueueEntityFactory::class)]
    public function list(): array;

    #[DbQuery('email_queues/email_queue_list')]
    #[Pager(perPage: 'perPage')]
    public function listByPerPage(int $perPage): PagesInterface;

    /** @return list<EmailQueueEntity> */
    #[DbQuery('email_queues/email_queue_list_by_sendable', factory: EmailQueueEntityFactory::class)]
    public function listBySendable(DateTimeInterface $scheduleDate): array;
}
