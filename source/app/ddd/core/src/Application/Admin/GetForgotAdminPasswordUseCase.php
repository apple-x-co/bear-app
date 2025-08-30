<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use AppCore\Domain\WebSignature\ExpiredSignatureException;
use AppCore\Domain\WebSignature\WebSignatureEncrypterInterface;
use DateTimeImmutable;

readonly class GetForgotAdminPasswordUseCase
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private WebSignatureEncrypterInterface $webSignatureEncrypter,
    ) {
    }

    public function execute(GetForgotAdminPasswordInputData $inputData): void
    {
        $webSignature = $this->webSignatureEncrypter->decrypt($inputData->signature);

        $now = new DateTimeImmutable();
        if ($webSignature->expiresDate < $now) {
            throw new ExpiredSignatureException();
        }
    }
}
