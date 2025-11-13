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
use AppCore\Domain\UrlSignature\ExpiredSignatureException;
use AppCore\Domain\UrlSignature\UrlSignatureEncrypterInterface;
use DateTimeImmutable;
use Ray\Di\Di\Named;

use function is_int;

/** @SuppressWarnings("PHPMD.CouplingBetweenObjects") */
readonly class CreateAdminUseCase
{
    /** @SuppressWarnings("PHPMD.LongVariable") */
    public function __construct(
        #[Named('admin')]
        private AddressInterface $adminAddress,
        private AdminPermissionRepositoryInterface $adminPermissionRepository,
        private AdminRepositoryInterface $adminRepository,
        private PasswordHasherInterface $passwordHasher,
        #[Named('SMTP')]
        private TransportInterface $smtpTransport,
        #[Named('queue')]
        private TransportInterface $queueTransport,
        private UrlSignatureEncrypterInterface $urlSignatureEncrypter,
    ) {
    }

    public function execute(CreateAdminInputData $inputData): void
    {
        $urlSignature = $this->urlSignatureEncrypter->decrypt($inputData->signature);
        $now = new DateTimeImmutable();
        if ($urlSignature->expiresDate < $now) {
            throw new ExpiredSignatureException();
        }

        $admin = new Admin(
            $inputData->username,
            $this->passwordHasher->hash($inputData->password),
            $inputData->displayName,
            true,
            [new AdminEmail($urlSignature->address, new DateTimeImmutable())],
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
                    ),
                );
            }
        }

        $this->queueTransport->send(
            (new Email())
                ->setFrom($this->adminAddress)
                ->setTo([new Address($urlSignature->address, $inputData->displayName)])
                ->setTemplateId('admin_welcome')
                ->setTemplateVars(['displayName' => $inputData->displayName])
                ->setScheduleDate(new DateTimeImmutable()),
        );

        $this->smtpTransport->send(
            (new Email())
                ->setFrom($this->adminAddress)
                ->setTo([new Address($urlSignature->address, $inputData->displayName)])
                ->setTemplateId('admin_sign_up')
                ->setTemplateVars(['displayName' => $inputData->displayName]),
        );
    }
}
