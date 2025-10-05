<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Command;

use AppCore\Application\Command\SendEmailFromEmailQueueUseCase;
use BEAR\Resource\ResourceObject;

class SendEmailFromEmailQueue extends ResourceObject
{
    public function __construct(
        private readonly SendEmailFromEmailQueueUseCase $sendQueueMailUseCase,
    ) {
    }

    /** @example "php ./bin/command.php post /send-email-from-email-queue" */
    public function onPost(): static
    {
        $this->sendQueueMailUseCase->execute();

        return $this;
    }
}
