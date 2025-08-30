<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\LoggerInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Infrastructure\Query\AdminDeleteCommandInterface;
use DateTimeImmutable;
use Ray\Di\Di\Named;
use Throwable;

readonly class DeleteAdminUseCase
{
    public function __construct(
        #[Named('admin')]
        private AddressInterface $adminAddress,
        private AdminDeleteCommandInterface $adminDeleteCommand,
        private AdminRepositoryInterface $adminRepository,
        #[Named('admin')]
        private LoggerInterface $logger,
        #[Named('SMTP')]
        private TransportInterface $transport,
    ) {
    }

    public function execute(DeleteAdminInputData $inputData): void
    {
        $this->adminDeleteCommand->add(
            $inputData->adminId,
            new DateTimeImmutable(),
            (new DateTimeImmutable())->modify('+1 day'),
        );

        $admin = $this->adminRepository->findById($inputData->adminId);
        foreach ($admin->emails as $adminEmail) {
            try {
                $this->transport->send(
                    (new Email())
                        ->setFrom($this->adminAddress)
                        ->setTo([new Address($adminEmail->emailAddress, $admin->username)])
                        ->setTemplateId('admin_deleted')
                        ->setTemplateVars(['displayName' => $admin->displayName]),
                );
            } catch (Throwable $throwable) {
                $this->logger->log((string) $throwable);
            }
        }
    }
}
