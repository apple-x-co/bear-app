<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use AppCore\Domain\AccessControl\Access;
use AppCore\Domain\AccessControl\Permission;
use AppCore\Domain\Admin\Admin;
use AppCore\Domain\Admin\AdminEmail;
use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\AdminPermission\AdminPermission;
use AppCore\Domain\AdminPermission\AdminPermissionRepositoryInterface;
use AppCore\Domain\Hasher\PasswordHasherInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\WebSignature\ExpiredSignatureException;
use AppCore\Domain\WebSignature\WebSignatureEncrypterInterface;
use DateTimeImmutable;
use Ray\Di\Di\Named;

use function is_int;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class CreateAdminUseCase
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        #[Named('admin')] private readonly AddressInterface $adminAddress,
        private readonly AdminPermissionRepositoryInterface $adminPermissionRepository,
        private readonly AdminRepositoryInterface $adminRepository,
        private readonly PasswordHasherInterface $passwordHasher,
        #[Named('SMTP')] private readonly TransportInterface $smtpTransport,
        #[Named('queue')] private readonly TransportInterface $queueTransport,
        private readonly WebSignatureEncrypterInterface $webSignatureEncrypter,
    ) {
    }

    public function execute(CreateAdminInputData $inputData): void
    {
        $webSignature = $this->webSignatureEncrypter->decrypt($inputData->signature);
        $now = new DateTimeImmutable();
        if ($webSignature->expiresAt < $now) {
            throw new ExpiredSignatureException();
        }

        $admin = new Admin(
            $inputData->username,
            $this->passwordHasher->hash($inputData->password),
            $inputData->displayName,
            true,
            [new AdminEmail($webSignature->address, new DateTimeImmutable())],
        );
        $this->adminRepository->store($admin);

        $adminId = $admin->getNewId();
        if (is_int($adminId)) {
            foreach (AdminPermission::DEFAULT_RESOURCE_NAMES as $resourceName) {
                $this->adminPermissionRepository->store(
                    new AdminPermission(
                        $adminId,
                        Access::Allow,
                        $resourceName,
                        Permission::Read,
                    )
                );
            }
        }

        $this->queueTransport->send(
            (new Email())
                ->setFrom($this->adminAddress)
                ->setTo([new Address($webSignature->address, $inputData->displayName)])
                ->setTemplateId('admin_welcome')
                ->setTemplateVars(['displayName' => $inputData->displayName])
                ->setScheduleAt(new DateTimeImmutable())
        );

        $this->smtpTransport->send(
            (new Email())
                ->setFrom($this->adminAddress)
                ->setTo([new Address($webSignature->address, $inputData->displayName)])
                ->setTemplateId('admin_sign_up')
                ->setTemplateVars(['displayName' => $inputData->displayName])
        );
    }
}
