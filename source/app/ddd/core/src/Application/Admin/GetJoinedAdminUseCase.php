<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use AppCore\Domain\WebSignature\ExpiredSignatureException;
use AppCore\Domain\WebSignature\UrlSignatureEncrypterInterface;
use DateTimeImmutable;

readonly class GetJoinedAdminUseCase
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private UrlSignatureEncrypterInterface $webSignatureEncrypter,
    ) {
    }

    public function execute(GetJoinedAdminInputData $inputData): void
    {
        $webSignature = $this->webSignatureEncrypter->decrypt($inputData->signature);

        $now = new DateTimeImmutable();
        if ($webSignature->expiresDate < $now) {
            throw new ExpiredSignatureException();
        }
    }
}
