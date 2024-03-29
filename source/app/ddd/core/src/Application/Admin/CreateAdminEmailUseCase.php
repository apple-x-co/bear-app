<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use AppCore\Domain\Admin\AdminEmail;
use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\Admin\EmailWebSignature;
use AppCore\Domain\LoggerInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\WebSignature\WebSignatureEncrypterInterface;
use BEAR\Sunday\Extension\Router\RouterInterface;
use DateTimeImmutable;
use Ray\Di\Di\Named;
use Throwable;

use function array_reduce;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class CreateAdminEmailUseCase
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        #[Named('admin')] private readonly AddressInterface $adminAddress,
        #[Named('admin_base_url')] private readonly string $adminBaseUrl,
        private readonly AdminRepositoryInterface $adminRepository,
        #[Named('admin')] private readonly LoggerInterface $logger,
        #[Named('SMTP')] private readonly TransportInterface $transport,
        private readonly RouterInterface $router,
        private readonly WebSignatureEncrypterInterface $webSignatureEncrypter
    ) {
    }

    public function execute(CreateAdminEmailInputData $inputData): void
    {
        $admin = $this->adminRepository->findById($inputData->adminId);

        $admin = $admin->addEmail(new AdminEmail($inputData->emailAddress));
        $this->adminRepository->store($admin);

        $expiresAt = (new DateTimeImmutable())->modify('+10 minutes');
        $emailWebSignature = new EmailWebSignature(
            $inputData->adminId,
            $expiresAt,
            $inputData->emailAddress,
        );
        $encrypted = $this->webSignatureEncrypter->encrypt($emailWebSignature);

        $this->transport->send(
            (new Email())
                ->setFrom($this->adminAddress)
                ->setTo([new Address($inputData->emailAddress)])
                ->setTemplateId('admin_email_verification')
                ->setTemplateVars([
                    'displayName' => $admin->displayName,
                    'expiresAt' => $expiresAt,
                    'adminBaseUrl' => $this->adminBaseUrl,
                    'verificationPathName' => $this->router->generate('/admin/email-verify', ['signature' => $encrypted]),
                ])
        );

        $notifyAddresses = array_reduce(
            $admin->emails,
            static function (array $carry, AdminEmail $item) {
                if ($item->verifiedAt === null) {
                    return $carry;
                }

                $carry[] = new Address($item->emailAddress);

                return $carry;
            },
            [],
        );

        if (empty($notifyAddresses)) {
            return;
        }

        try {
            $this->transport->send(
                (new Email())
                    ->setFrom($this->adminAddress)
                    ->setTo($notifyAddresses)
                    ->setTemplateId('admin_email_created')
                    ->setTemplateVars([
                        'displayName' => $admin->displayName,
                        'emailAddress' => $inputData->emailAddress,
                    ])
            );
        } catch (Throwable $throwable) {
            $this->logger->log((string) $throwable);
        }
    }
}
