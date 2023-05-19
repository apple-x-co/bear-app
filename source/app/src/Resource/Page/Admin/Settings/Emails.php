<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin\Settings;

use AppCore\Domain\Admin\AdminEmail;
use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\EncrypterInterface;
use AppCore\Domain\LoggerInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\SecureRandomInterface;
use AppCore\Domain\VerifyEmail\VerifyEmailSignature;
use DateTimeImmutable;
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Annotation\AdminGuard;
use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use MyVendor\MyProject\Input\Admin\CreateEmailInput;
use MyVendor\MyProject\Lang\LanguageInterface;
use MyVendor\MyProject\Resource\Page\AdminPage;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;
use Throwable;

use function array_reduce;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 */
class Emails extends AdminPage
{
    public function __construct(
        #[Named('admin')] private readonly AddressInterface $adminAddress,
        private readonly AdminAuthenticatorInterface $adminAuthenticator,
        #[Named('admin_base_url')] private readonly string $adminBaseUrl,
        private readonly AdminRepositoryInterface $adminRepository,
        private readonly EncrypterInterface $encrypter,
        #[Named('admin_email_create_form')] protected readonly FormInterface $form,
        private readonly LanguageInterface $language,
        #[Named('admin')] private readonly LoggerInterface $logger,
        private readonly SecureRandomInterface $secureRandom,
        #[Named('SMTP')] private readonly TransportInterface $transport,
    ) {
        $this->body['form'] = $this->form;
    }

    #[AdminGuard]
    public function onGet(): static
    {
        $adminId = (int) $this->adminAuthenticator->getUserId();
        $admin = $this->adminRepository->findById($adminId);

        $this->body['admin'] = $admin;

        return $this;
    }

    /**
     * @FormValidation()
     */
    #[AdminGuard]
    #[Transactional]
    public function onPost(CreateEmailInput $createEmail): static
    {
        $adminId = (int) $this->adminAuthenticator->getUserId();
        $admin = $this->adminRepository->findById($adminId);

        $admin = $admin->addEmail(
            new AdminEmail(
                $adminId,
                $createEmail->emailAddress,
            )
        );
        $this->adminRepository->store($admin);

        $verifyEmailSignature = new VerifyEmailSignature(
            $this->secureRandom->randomBytes(5),
            (new DateTimeImmutable())->modify('+10 minutes'),
            $adminId,
            $createEmail->emailAddress,
        );
        $encrypted = $this->encrypter->encrypt($verifyEmailSignature->serialize());
        $this->transport->send(
            (new Email())
                ->setFrom($this->adminAddress)
                ->setTo([new Address($createEmail->emailAddress)])
                ->setTemplate('admin_email_verification')
                ->setTemplateVars([
                    'displayName' => $admin->displayName,
                    'expiresAt' => $verifyEmailSignature->expiresAt,
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
            $this->renderer = null;
            $this->session->setFlashMessage($this->language->get('message:admin:email_created'));
            $this->code = StatusCode::SEE_OTHER;
            $this->headers = [ResponseHeader::LOCATION => '/admin/settings/index']; // 注意：フォームがある画面に戻るとフラッシュメッセージが表示されない

            return $this;
        }

        try {
            $this->transport->send(
                (new Email())
                    ->setFrom($this->adminAddress)
                    ->setTo($notifyAddresses)
                    ->setTemplate('admin_email_created')
                    ->setTemplateVars([
                        'displayName' => $admin->displayName,
                        'emailAddress' => $verifyEmailSignature->address,
                    ])
            );
        } catch (Throwable $throwable) {
            $this->logger->log((string) $throwable);
        }

        $this->renderer = null;
        $this->session->setFlashMessage($this->language->get('message:admin:email_created'));
        $this->code = StatusCode::SEE_OTHER;
        $this->headers = [ResponseHeader::LOCATION => '/admin/settings/index']; // 注意：フォームがある画面に戻るとフラッシュメッセージが表示されない

        return $this;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function onPostValidationFailed(): static
    {
        $adminId = (int) $this->adminAuthenticator->getUserId();
        $admin = $this->adminRepository->findById($adminId);

        $this->body['admin'] = $admin;

        return $this;
    }
}
