<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin\Settings;

use AppCore\Domain\AccessControl\Permission;
use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\Hasher\PasswordHasherInterface;
use AppCore\Domain\Language\LanguageInterface;
use AppCore\Domain\LoggerInterface;
use AppCore\Domain\Mail\Address;
use AppCore\Domain\Mail\AddressInterface;
use AppCore\Domain\Mail\Email;
use AppCore\Domain\Mail\TransportInterface;
use AppCore\Infrastructure\Query\AdminPasswordUpdateInterface;
use BEAR\Resource\NullRenderer;
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Annotation\AdminGuard;
use MyVendor\MyProject\Annotation\RequiredPermission;
use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use MyVendor\MyProject\Auth\AuthenticationException;
use MyVendor\MyProject\Auth\PasswordIncorrect;
use MyVendor\MyProject\Input\Admin\UpdatePasswordInput;
use MyVendor\MyProject\Resource\Page\AdminPage;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;
use Throwable;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class Password extends AdminPage
{
    public function __construct(
        #[Named('admin')] private readonly AddressInterface $adminAddress,
        private readonly AdminAuthenticatorInterface $adminAuthenticator,
        private readonly AdminRepositoryInterface $adminRepository,
        private readonly AdminPasswordUpdateInterface $adminPasswordUpdate,
        #[Named('admin_password_update_form')] protected readonly FormInterface $form,
        private readonly LanguageInterface $language,
        private readonly PasswordHasherInterface $passwordHasher,
        #[Named('admin')] private readonly LoggerInterface $logger,
        #[Named('SMTP')] private readonly TransportInterface $transport,
    ) {
        $this->body['form'] = $this->form;
    }

    #[AdminGuard]
    #[RequiredPermission('Settings', Permission::Read)]
    public function onGet(): static
    {
        return $this;
    }

    /**
     * @FormValidation()
     */
    #[AdminGuard]
    public function onPost(UpdatePasswordInput $updatePassword): static
    {
        $userName = $this->adminAuthenticator->getUserName();
        $adminId = $this->adminAuthenticator->getUserId();
        if ($userName === null || $adminId === null) {
            return $this;
        }

        try {
            $this->adminAuthenticator->verifyPassword(
                $userName,
                $updatePassword->oldPassword,
            );
        } catch (Throwable $throwable) {
            return $this->onPostPasswordNotMatched(
                new PasswordIncorrect(
                    $throwable->getMessage(),
                    (int) $throwable->getCode(),
                    $throwable->getPrevious()
                )
            );
        }

        ($this->adminPasswordUpdate)($adminId, $this->passwordHasher->hash($updatePassword->password));

        $admin = $this->adminRepository->findById($adminId);
        foreach ($admin->emails as $adminEmail) {
            try {
                $this->transport->send(
                    (new Email())
                        ->setFrom($this->adminAddress)
                        ->setTo([new Address($adminEmail->emailAddress, $admin->username)])
                        ->setTemplateId('admin_password_updated')
                        ->setTemplateVars(['displayName' => $admin->displayName])
                );
            } catch (Throwable $throwable) {
                $this->logger->log((string) $throwable);
            }
        }

        $this->renderer = new NullRenderer();
        $this->session->setFlashMessage($this->language->get('message:admin:password_updated'));
        $this->code = StatusCode::SEE_OTHER;
        $this->headers = [ResponseHeader::LOCATION => '/admin/settings/index']; // 注意：フォームがある画面に戻るとフラッシュメッセージが表示されない

        return $this;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function onPostValidationFailed(): static
    {
        return $this;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function onPostPasswordNotMatched(AuthenticationException $authException): static
    {
        $this->body['authError'] = true;

        return $this;
    }
}
