<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Command;

use AppCore\Application\Command\SendEmailQueueUseCase;
use BEAR\Resource\ResourceObject;

use function array_map;

class SendEmailFromEmailQueue extends ResourceObject
{
    /** @SuppressWarnings("PHPMD.LongVariable") */
    public function __construct(
        private readonly SendEmailQueueUseCase $sendEmailQueueUseCase,
    ) {
    }

    /** @example "php ./bin/command.php post /send-email-from-email-queue" */
    public function onPost(): static
    {
        $outputData = $this->sendEmailQueueUseCase->execute();

        $this->body['emailQueueList'] = array_map(
            static fn (array $item) => ['emailQueueId' => $item['emailQueueId']],
            $outputData->emailQueueList,
        );

        return $this;
    }
}
