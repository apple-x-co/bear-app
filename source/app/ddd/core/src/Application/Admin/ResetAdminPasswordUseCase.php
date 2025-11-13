<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use AppCore\Domain\Hasher\PasswordHasherInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\UrlSignature\ExpiredSignatureException;
use AppCore\Domain\UrlSignature\UrlSignatureEncrypterInterface;
use AppCore\Infrastructure\Query\AdminPasswordUpdateInterface;
use AppCore\Infrastructure\Query\AdminQueryInterface;
use DateTimeImmutable;
use Ray\Di\Di\Named;

/** @SuppressWarnings("PHPMD.CouplingBetweenObjects") */
readonly class ResetAdminPasswordUseCase
{
    /** @SuppressWarnings("PHPMD.LongVariable") */
    public function __construct(
        #[Named('admin')]
        private AddressInterface $adminAddress,
        private AdminQueryInterface $adminQuery,
        private AdminPasswordUpdateInterface $adminPasswordUpdate,
        private PasswordHasherInterface $passwordHasher,
        #[Named('SMTP')]
        private TransportInterface $transport,
        private UrlSignatureEncrypterInterface $urlSignatureEncrypter,
    ) {
    }

    public function execute(ResetAdminPasswordInputData $inputData): void
    {
        $urlSignature = $this->urlSignatureEncrypter->decrypt($inputData->signature);
        $now = new DateTimeImmutable();
        if ($urlSignature->expiresDate < $now) {
            throw new ExpiredSignatureException();
        }

        $adminEntity = $this->adminQuery->itemByEmailAddress($urlSignature->address);
        if ($adminEntity === null) {
            return;
        }

        ($this->adminPasswordUpdate)($adminEntity->id, $this->passwordHasher->hash($inputData->password));
        $this->transport->send(
            (new Email())
                ->setFrom($this->adminAddress)
                ->setTo([new Address($urlSignature->address)])
                ->setTemplateId('admin_password_reset')
                ->setTemplateVars(['displayName' => $adminEntity->displayName]),
        );
    }
}
