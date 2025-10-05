<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Command;

use AppCore\Application\Command\ImportBadPasswordUseCase;
use BEAR\Resource\ResourceObject;
use Ray\AuraSqlModule\Annotation\Transactional;

/** @SuppressWarnings(PHPMD.LongVariable) */
class ImportBadPasswords extends ResourceObject
{
    public function __construct(
        private readonly ImportBadPasswordUseCase $importBadPasswordUseCase,
    ) {
    }

    /** @example "php ./bin/command.php post /import-bad-passwords" */
    #[Transactional]
    public function onPost(): static
    {
        $outputData = $this->importBadPasswordUseCase->execute();

        $this->body['passwords'] = $outputData->passwords;

        return $this;
    }
}
