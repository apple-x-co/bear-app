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
use AppCore\Domain\WebSignature\ExpiredSignatureException;
use AppCore\Domain\WebSignature\WebSignatureEncrypterInterface;
use AppCore\Domain\WebSignature\WrongEmailVerifyException;
use DateTimeImmutable;
use Ray\Di\Di\Named;
use Throwable;

use function array_reduce;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class VerifyAdminEmailUseCase
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        #[Named('admin')] private readonly AddressInterface $adminAddress,
        private readonly AdminRepositoryInterface $adminRepository,
        #[Named('admin')] private readonly LoggerInterface $logger,
        #[Named('SMTP')] private readonly TransportInterface $transport,
        private readonly WebSignatureEncrypterInterface $webSignatureEncrypter,
    ) {
    }

    public function execute(VerifyAdminEmailInputData $inputData): void
    {
        $emailWebSignature = $this->webSignatureEncrypter->decrypt($inputData->signature, EmailWebSignature::class);
        $now = new DateTimeImmutable();
        if ($emailWebSignature->expiresAt < $now) {
            throw new ExpiredSignatureException();
        }

        if ($inputData->adminId !== $emailWebSignature->adminId) {
            throw new WrongEmailVerifyException();
        }

        $admin = $this->adminRepository->findById($inputData->adminId);
        $emailAddressMap = array_reduce(
            $admin->emails,
            static function (array $carry, AdminEmail $item) {
                $carry[$item->emailAddress] = $item;

                return $carry;
            },
            []
        );
        if (! isset($emailAddressMap[$emailWebSignature->address])) {
            throw new WrongEmailVerifyException();
        }

        $this->adminRepository->store(
            $admin->markEmailAsVerified($emailWebSignature->address)
        );

        try {
            $this->transport->send(
                (new Email())
                    ->setFrom($this->adminAddress)
                    ->setTo([new Address($emailWebSignature->address)])
                    ->setTemplateId('admin_email_verified')
                    ->setTemplateVars([
                        'displayName' => $admin->displayName,
                    ])
            );
        } catch (Throwable $throwable) {
            $this->logger->log((string) $throwable);
        }
    }
}
