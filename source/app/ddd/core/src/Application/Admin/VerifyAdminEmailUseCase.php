<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use AppCore\Domain\Admin\AdminEmail;
use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\Admin\EmailUrlSignature;
use AppCore\Domain\LoggerInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\UrlSignature\ExpiredSignatureException;
use AppCore\Domain\UrlSignature\UrlSignatureEncrypterInterface;
use AppCore\Domain\UrlSignature\WrongEmailVerifyException;
use DateTimeImmutable;
use Ray\Di\Di\Named;
use Throwable;

use function array_reduce;

/** @SuppressWarnings("PHPMD.CouplingBetweenObjects") */
readonly class VerifyAdminEmailUseCase
{
    /** @SuppressWarnings("PHPMD.LongVariable") */
    public function __construct(
        #[Named('admin')]
        private AddressInterface $adminAddress,
        private AdminRepositoryInterface $adminRepository,
        #[Named('admin')]
        private LoggerInterface $logger,
        #[Named('SMTP')]
        private TransportInterface $transport,
        private UrlSignatureEncrypterInterface $urlSignatureEncrypter,
    ) {
    }

    public function execute(VerifyAdminEmailInputData $inputData): void
    {
        $emailUrlSignature = $this->urlSignatureEncrypter->decrypt($inputData->signature, EmailUrlSignature::class);
        $now = new DateTimeImmutable();
        if ($emailUrlSignature->expiresDate < $now) {
            throw new ExpiredSignatureException();
        }

        if ($inputData->adminId !== $emailUrlSignature->adminId) {
            throw new WrongEmailVerifyException();
        }

        $admin = $this->adminRepository->findById($inputData->adminId);
        $emailAddressMap = array_reduce(
            $admin->emails,
            static function (array $carry, AdminEmail $item) {
                $carry[$item->emailAddress] = $item;

                return $carry;
            },
            [],
        );
        if (! isset($emailAddressMap[$emailUrlSignature->address])) {
            throw new WrongEmailVerifyException();
        }

        $this->adminRepository->store($admin->markEmailAsVerified($emailUrlSignature->address));

        try {
            $this->transport->send(
                (new Email())
                    ->setFrom($this->adminAddress)
                    ->setTo([new Address($emailUrlSignature->address)])
                    ->setTemplateId('admin_email_verified')
                    ->setTemplateVars([
                        'displayName' => $admin->displayName,
                    ]),
            );
        } catch (Throwable $throwable) {
            $this->logger->log((string) $throwable);
        }
    }
}
