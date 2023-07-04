<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin\Settings;

use AppCore\Application\Admin\DeleteAdminInputData;
use AppCore\Application\Admin\DeleteAdminUseCase;
use AppCore\Domain\AccessControl\Permission;
use MyVendor\MyProject\Annotation\AdminGuard;
use MyVendor\MyProject\Annotation\AdminLogout;
use MyVendor\MyProject\Annotation\AdminPasswordProtect;
use MyVendor\MyProject\Annotation\RequiredPermission;
use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use MyVendor\MyProject\Input\Admin\DeleteInput;
use MyVendor\MyProject\Resource\Page\AdminPage;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class Delete extends AdminPage
{
    public function __construct(
        private readonly AdminAuthenticatorInterface $adminAuthenticator,
        private readonly DeleteAdminUseCase $deleteAdminUseCase,
        #[Named('admin_delete_form')] protected readonly FormInterface $form,
    ) {
        $this->body['form'] = $this->form;
    }

    #[AdminGuard]
    #[AdminPasswordProtect]
    #[RequiredPermission('settings', Permission::Read)]
    public function onGet(): static
    {
        return $this;
    }

    /**
     * @FormValidation()
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    #[AdminGuard]
    #[Transactional]
    #[AdminLogout]
    public function onPost(DeleteInput $delete): static
    {
        $this->deleteAdminUseCase->execute(
            new DeleteAdminInputData((int) $this->adminAuthenticator->getUserId())
        );

        return $this;
    }

    public function onPostValidationFailed(): static
    {
        return $this;
    }
}
