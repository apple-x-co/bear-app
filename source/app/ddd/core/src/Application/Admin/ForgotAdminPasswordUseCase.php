<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\SecureRandom\SecureRandomInterface;
use AppCore\Domain\WebSignature\WebSignature;
use AppCore\Domain\WebSignature\WebSignatureEncrypterInterface;
use AppCore\Infrastructure\Query\AdminQueryInterface;
use AppCore\Infrastructure\Query\VerificationCodeCommandInterface;
use BEAR\Sunday\Extension\Router\RouterInterface;
use DateTimeImmutable;
use Ray\Di\Di\Named;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class ForgotAdminPasswordUseCase
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        #[Named('admin')] private readonly AddressInterface $adminAddress,
        #[Named('admin_base_url')] private readonly string $adminBaseUrl,
        private readonly AdminQueryInterface $adminQuery,
        private readonly SecureRandomInterface $secureRandom,
        #[Named('SMTP')] private readonly TransportInterface $transport,
        private readonly RouterInterface $router,
        private readonly VerificationCodeCommandInterface $verificationCodeCommand,
        private readonly WebSignatureEncrypterInterface $webSignatureEncrypter,
    ) {
    }

    public function execute(ForgotAdminPasswordInputData $inputData): ForgotAdminPasswordOutputData
    {
        $expiresAt = (new DateTimeImmutable())->modify('+10 minutes');
        $code = $this->secureRandom->randomNumbers(12);

        $adminEntity = $this->adminQuery->itemByEmailAddress($inputData->emailAddress);
        $this->transport->send(
            (new Email())
                ->setFrom($this->adminAddress)
                ->setTo([new Address($inputData->emailAddress)])
                ->setTemplateId('admin_password_forgot')
                ->setTemplateVars([
                    'displayName' => $adminEntity?->displayName,
                    'expiresAt' => $expiresAt,
                    'code' => $code,
                ])
        );

        $webSignature = new WebSignature(
            $expiresAt,
            $inputData->emailAddress,
        );
        $encrypted = $this->webSignatureEncrypter->encrypt($webSignature);

        $array = $this->verificationCodeCommand->add(
            $inputData->emailAddress,
            $this->adminBaseUrl . (string) $this->router->generate('/admin/reset-password', ['signature' => $encrypted]),
            (string) $code,
            $expiresAt,
        );

        return new ForgotAdminPasswordOutputData(
            (string) $this->router->generate('/admin/code-verify', ['uuid' => $array['uuid']]),
        );
    }
}
