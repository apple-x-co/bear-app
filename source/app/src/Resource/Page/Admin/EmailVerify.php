<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

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
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Annotation\AdminGuard;
use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use MyVendor\MyProject\Lang\LanguageInterface;
use MyVendor\MyProject\Resource\Page\AdminPage;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\AuraSqlModule\Annotation\WriteConnection;
use Ray\Di\Di\Named;
use Throwable;

use function array_reduce;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class EmailVerify extends AdminPage
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        #[Named('admin')] private readonly AddressInterface $adminAddress,
        private readonly AdminAuthenticatorInterface $adminAuthenticator,
        private readonly AdminRepositoryInterface $adminRepository,
        private readonly LanguageInterface $language,
        #[Named('admin')] private readonly LoggerInterface $logger,
        #[Named('SMTP')] private readonly TransportInterface $transport,
        private readonly WebSignatureEncrypterInterface $webSignatureEncrypter,
    ) {
    }

    /** @SuppressWarnings(PHPMD.StaticAccess) */
    #[AdminGuard]
    #[WriteConnection]
    #[Transactional]
    public function onGet(string $signature): static
    {
        $emailWebSignature = $this->webSignatureEncrypter->decrypt($signature, EmailWebSignature::class);
        $now = new DateTimeImmutable();
        if ($emailWebSignature->expiresAt < $now) {
            throw new ExpiredSignatureException();
        }

        $adminId = (int) $this->adminAuthenticator->getUserId();
        if ($adminId !== $emailWebSignature->adminId) {
            throw new WrongEmailVerifyException();
        }

        $admin = $this->adminRepository->findById($adminId);
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

        $this->renderer = null;
        $this->session->setFlashMessage($this->language->get('message:admin:email_verified'));
        $this->code = StatusCode::SEE_OTHER;
        $this->headers = [ResponseHeader::LOCATION => '/admin/settings/index']; // 注意：フォームがある画面に戻るとフラッシュメッセージが表示されない

        return $this;
    }
}
