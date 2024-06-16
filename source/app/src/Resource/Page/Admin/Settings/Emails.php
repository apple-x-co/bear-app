<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin\Settings;

use AppCore\Application\Admin\CreateAdminEmailInputData;
use AppCore\Application\Admin\CreateAdminEmailUseCase;
use AppCore\Application\Admin\GetAdminInputData;
use AppCore\Application\Admin\GetAdminUseCase;
use AppCore\Domain\AccessControl\Permission;
use AppCore\Domain\Language\LanguageInterface;
use BEAR\Resource\NullRenderer;
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Annotation\AdminGuard;
use MyVendor\MyProject\Annotation\RequiredPermission;
use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use MyVendor\MyProject\Input\Admin\CreateEmailInput;
use MyVendor\MyProject\Resource\Page\AdminPage;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

class Emails extends AdminPage
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private readonly AdminAuthenticatorInterface $adminAuthenticator,
        private readonly CreateAdminEmailUseCase $createAdminEmailUseCase,
        private readonly GetAdminUseCase $getAdminUseCase,
        #[Named('admin_email_create_form')] protected readonly FormInterface $form,
        private readonly LanguageInterface $language,
    ) {
        $this->body['form'] = $this->form;
    }

    #[AdminGuard]
    #[RequiredPermission('settings', Permission::Read)]
    public function onGet(): static
    {
        $outputData = $this->getAdminUseCase->execute(
            new GetAdminInputData((int) $this->adminAuthenticator->getUserId())
        );

        $this->body['admin'] = $outputData->admin;

        return $this;
    }

    /**
     * @FormValidation()
     */
    #[AdminGuard]
    #[Transactional]
    public function onPost(CreateEmailInput $createEmail): static
    {
        $this->createAdminEmailUseCase->execute(
            new CreateAdminEmailInputData(
                (int) $this->adminAuthenticator->getUserId(),
                $createEmail->emailAddress,
            )
        );

        $this->renderer = new NullRenderer();
        $this->code = StatusCode::SEE_OTHER;
        $this->headers = [ResponseHeader::LOCATION => '/admin/settings/index']; // 注意：フォームがある画面に戻るとフラッシュメッセージが表示されない
        $this->context->setFlashMessage($this->language->get('message:admin:email_created'));

        return $this;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function onPostValidationFailed(): static
    {
        $outputData = $this->getAdminUseCase->execute(
            new GetAdminInputData((int) $this->adminAuthenticator->getUserId())
        );

        $this->body['admin'] = $outputData->admin;

        return $this;
    }
}
