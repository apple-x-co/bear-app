<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\Hasher\PasswordHasherInterface;
use AppCore\Domain\LoggerInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Infrastructure\Query\AdminPasswordUpdateInterface;
use Ray\Di\Di\Named;
use Throwable;

class UpdateAdminPasswordUseCase
{
    public function __construct(
        #[Named('admin')] private readonly AddressInterface $adminAddress,
        private readonly AdminRepositoryInterface $adminRepository,
        private readonly AdminPasswordUpdateInterface $adminPasswordUpdate,
        private readonly PasswordHasherInterface $passwordHasher,
        #[Named('admin')] private readonly LoggerInterface $logger,
        #[Named('SMTP')] private readonly TransportInterface $transport,
    ) {
    }

    public function execute(UpdateAdminPasswordInputData $inputData): void
    {
        ($this->adminPasswordUpdate)(
            $inputData->adminId,
            $this->passwordHasher->hash($inputData->password)
        );

        $admin = $this->adminRepository->findById($inputData->adminId);
        foreach ($admin->emails as $adminEmail) {
            try {
                $this->transport->send(
                    (new Email())
                        ->setFrom($this->adminAddress)
                        ->setTo([new Address($adminEmail->emailAddress, $admin->username)])
                        ->setTemplateId('admin_password_updated')
                        ->setTemplateVars(['displayName' => $admin->displayName])
                );
            } catch (Throwable $throwable) {
                $this->logger->log((string) $throwable);
            }
        }
    }
}
