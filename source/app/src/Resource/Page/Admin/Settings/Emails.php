<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin\Settings;

use AppCore\Domain\Admin\AdminEmail;
use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\Admin\EmailWebSignature;
use AppCore\Domain\LoggerInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Domain\WebSignature\WebSignatureEncrypterInterface;
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
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        #[Named('admin')] private readonly AddressInterface $adminAddress,
        private readonly AdminAuthenticatorInterface $adminAuthenticator,
        #[Named('admin_base_url')] private readonly string $adminBaseUrl,
        private readonly AdminRepositoryInterface $adminRepository,
        #[Named('admin_email_create_form')] protected readonly FormInterface $form,
        private readonly LanguageInterface $language,
        #[Named('admin')] private readonly LoggerInterface $logger,
        #[Named('SMTP')] private readonly TransportInterface $transport,
        private readonly WebSignatureEncrypterInterface $webSignatureEncrypter,
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
        $admin = $admin->addEmail(new AdminEmail($createEmail->emailAddress));
        $this->adminRepository->store($admin);

        $expiresAt = (new DateTimeImmutable())->modify('+10 minutes');
        $emailWebSignature = new EmailWebSignature(
            $adminId,
            $expiresAt,
            $createEmail->emailAddress,
        );
        $encrypted = $this->webSignatureEncrypter->encrypt($emailWebSignature);

        $this->transport->send(
            (new Email())
                ->setFrom($this->adminAddress)
                ->setTo([new Address($createEmail->emailAddress)])
                ->setTemplate('admin_email_verification')
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
                        'emailAddress' => $createEmail->emailAddress,
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
