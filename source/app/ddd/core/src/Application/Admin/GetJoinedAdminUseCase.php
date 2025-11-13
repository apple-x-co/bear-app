<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use AppCore\Domain\UrlSignature\ExpiredSignatureException;
use AppCore\Domain\UrlSignature\UrlSignatureEncrypterInterface;
use DateTimeImmutable;

readonly class GetJoinedAdminUseCase
{
    /** @SuppressWarnings("PHPMD.LongVariable") */
    public function __construct(
        private UrlSignatureEncrypterInterface $urlSignatureEncrypter,
    ) {
    }

    public function execute(GetJoinedAdminInputData $inputData): void
    {
        $urlSignature = $this->urlSignatureEncrypter->decrypt($inputData->signature);

        $now = new DateTimeImmutable();
        if ($urlSignature->expiresDate < $now) {
            throw new ExpiredSignatureException();
        }
    }
}
