<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin\Settings;

use AppCore\Application\Admin\UpdateAdminPasswordInputData;
use AppCore\Application\Admin\UpdateAdminPasswordUseCase;
use AppCore\Domain\AccessControl\Permission;
use AppCore\Domain\Language\LanguageInterface;
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
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private readonly AdminAuthenticatorInterface $adminAuthenticator,
        #[Named('admin_password_update_form')] protected readonly FormInterface $form,
        private readonly LanguageInterface $language,
        private readonly UpdateAdminPasswordUseCase $updateAdminPasswordUseCase,
    ) {
        $this->body['form'] = $this->form;
    }

    #[AdminGuard]
    #[RequiredPermission('settings', Permission::Read)]
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

        $this->updateAdminPasswordUseCase->execute(
            new UpdateAdminPasswordInputData(
                $adminId,
                $userName,
                $updatePassword->password,
            )
        );

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
