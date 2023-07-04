<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use AppCore\Domain\Hasher\PasswordHasherInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\WebSignature\ExpiredSignatureException;
use AppCore\Domain\WebSignature\WebSignatureEncrypterInterface;
use AppCore\Infrastructure\Query\AdminPasswordUpdateInterface;
use AppCore\Infrastructure\Query\AdminQueryInterface;
use DateTimeImmutable;
use Ray\Di\Di\Named;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class ResetAdminPasswordUseCase
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        #[Named('admin')] private readonly AddressInterface $adminAddress,
        private readonly AdminQueryInterface $adminQuery,
        private readonly AdminPasswordUpdateInterface $adminPasswordUpdate,
        private readonly PasswordHasherInterface $passwordHasher,
        #[Named('SMTP')] private readonly TransportInterface $transport,
        private readonly WebSignatureEncrypterInterface $webSignatureEncrypter,
    ) {
    }

    public function execute(ResetAdminPasswordInputData $inputData): void
    {
        $webSignature = $this->webSignatureEncrypter->decrypt($inputData->signature);
        $now = new DateTimeImmutable();
        if ($webSignature->expiresAt < $now) {
            throw new ExpiredSignatureException();
        }

        $adminEntity = $this->adminQuery->itemByEmailAddress($webSignature->address);
        if ($adminEntity === null) {
            return;
        }

        ($this->adminPasswordUpdate)($adminEntity->id, $this->passwordHasher->hash($inputData->password));
        $this->transport->send(
            (new Email())
                ->setFrom($this->adminAddress)
                ->setTo([new Address($webSignature->address)])
                ->setTemplateId('admin_password_reset')
                ->setTemplateVars(['displayName' => $adminEntity->displayName])
        );
    }
}
