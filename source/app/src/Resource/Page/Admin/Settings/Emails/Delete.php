<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin\Settings\Emails;

use AppCore\Application\Admin\DeleteAdminEmailInputData;
use AppCore\Application\Admin\DeleteAdminEmailUseCase;
use AppCore\Domain\AccessControl\Permission;
use AppCore\Domain\Auth\AdminAuthenticatorInterface;
use AppCore\Domain\Language\LanguageInterface;
use BEAR\Resource\NullRenderer;
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Annotation\AdminGuard;
use MyVendor\MyProject\Annotation\RequiredPermission;
use MyVendor\MyProject\InputQuery\Admin\DeleteEmailInput;
use MyVendor\MyProject\Resource\Page\AdminPage;
use Ray\InputQuery\Attribute\Input;

class Delete extends AdminPage
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private readonly AdminAuthenticatorInterface $adminAuthenticator,
        private readonly DeleteAdminEmailUseCase $deleteAdminEmailUseCase,
        private readonly LanguageInterface $language,
    ) {
    }

    #[AdminGuard]
    #[RequiredPermission('settings', Permission::Read)]
    public function onPost(
        #[Input]
        DeleteEmailInput $input,
    ): static {
        $this->deleteAdminEmailUseCase->execute(
            new DeleteAdminEmailInputData(
                (int) $this->adminAuthenticator->getUserId(),
                $input->id,
            ),
        );

        $this->renderer = new NullRenderer();
        $this->code = StatusCode::SEE_OTHER;
        $this->headers = [ResponseHeader::LOCATION => '/admin/settings/index']; // 注意：フォームがある画面に戻るとフラッシュメッセージが表示されない
        $this->context->setFlashMessage($this->language->get('message:admin:email_deleted'));

        return $this;
    }
}
