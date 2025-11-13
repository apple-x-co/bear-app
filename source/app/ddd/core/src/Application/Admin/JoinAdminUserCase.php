<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use AppCore\Attribute\AdminBaseUrl;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\SecureRandom\SecureRandomInterface;
use AppCore\Domain\UrlSignature\UrlSignature;
use AppCore\Domain\UrlSignature\UrlSignatureEncrypterInterface;
use AppCore\Infrastructure\Query\AdminQueryInterface;
use AppCore\Infrastructure\Query\VerificationCodeCommandInterface;
use BEAR\Sunday\Extension\Router\RouterInterface;
use DateTimeImmutable;
use Ray\Di\Di\Named;

/** @SuppressWarnings("PHPMD.CouplingBetweenObjects") */
readonly class JoinAdminUserCase
{
    /** @SuppressWarnings("PHPMD.LongVariable") */
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
        private UrlSignatureEncrypterInterface $urlSignatureEncrypter,
    ) {
    }

    public function execute(JoinAdminInputData $inputData): JoinAdminOutputData
    {
        $expiresAt = (new DateTimeImmutable())->modify('+10 minutes');
        $code = $this->secureRandom->randomNumbers(12);

        $adminEntity = $this->adminQuery->itemByEmailAddress($inputData->emailAddress);
        if ($adminEntity === null) {
            $this->transport->send(
                (new Email())
                    ->setFrom($this->adminAddress)
                    ->setTo([new Address($inputData->emailAddress)])
                    ->setTemplateId('admin_join')
                    ->setTemplateVars([
                        'expiresAt' => $expiresAt,
                        'code' => $code,
                    ]),
            );
        }

        $urlSignature = new UrlSignature(
            $expiresAt,
            $inputData->emailAddress,
        );
        $encrypted = $this->urlSignatureEncrypter->encrypt($urlSignature);

        $array = $this->verificationCodeCommand->add(
            $inputData->emailAddress,
            $this->adminBaseUrl . (string) $this->router->generate('/admin/sign-up', ['signature' => $encrypted]),
            (string) $code,
            $expiresAt,
        );

        return new JoinAdminOutputData((string) $this->router->generate('/admin/code-verify', ['uuid' => $array['uuid']]));
    }
}
