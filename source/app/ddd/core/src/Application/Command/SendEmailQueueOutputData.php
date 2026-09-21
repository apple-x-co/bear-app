<?php

declare(strict_types=1);

namespace AppCore\Application\Command;

final class SendEmailQueueOutputData
{
    /** @param list<array{emailQueueId: positive-int}> $emailQueueList */
    public function __construct(
        public array $emailQueueList,
    ) {
    }
}
