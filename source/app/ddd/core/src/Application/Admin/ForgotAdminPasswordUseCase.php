<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use AppCore\Attribute\AdminBaseUrl;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\SecureRandom\SecureRandomInterface;
use AppCore\Domain\WebSignature\UrlSignature;
use AppCore\Domain\WebSignature\UrlSignatureEncrypterInterface;
use AppCore\Infrastructure\Query\AdminQueryInterface;
use AppCore\Infrastructure\Query\VerificationCodeCommandInterface;
use BEAR\Sunday\Extension\Router\RouterInterface;
use DateTimeImmutable;
use Ray\Di\Di\Named;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
readonly class ForgotAdminPasswordUseCase
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        #[Named('admin')]
        private AddressInterface $adminAddress,
        #[AdminBaseUrl]
        private string $adminBaseUrl,
        private AdminQueryInterface $adminQuery,
        private SecureRandomInterface $secureRandom,
        #[Named('SMTP')]
        private TransportInterface $transport,
        private RouterInterface $router,
        private VerificationCodeCommandInterface $verificationCodeCommand,
        private UrlSignatureEncrypterInterface $webSignatureEncrypter,
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
                ]),
        );

        $webSignature = new UrlSignature(
            $expiresAt,
            $inputData->emailAddress,
        );
        $encrypted = $this->webSignatureEncrypter->encrypt($webSignature);

        $array = $this->verificationCodeCommand->add(
            $inputData->emailAddress,
            $this->adminBaseUrl . (string) $this->router->generate(
                '/admin/reset-password',
                ['signature' => $encrypted],
            ),
            (string) $code,
            $expiresAt,
        );

        return new ForgotAdminPasswordOutputData(
            (string) $this->router->generate('/admin/code-verify', ['uuid' => $array['uuid']]),
        );
    }
}
