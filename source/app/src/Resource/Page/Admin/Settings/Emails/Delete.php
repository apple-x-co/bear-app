<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin\Settings\Emails;

use AppCore\Domain\AccessControl\Permission;
use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\Language\LanguageInterface;
use BEAR\Resource\NullRenderer;
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Annotation\AdminGuard;
use MyVendor\MyProject\Annotation\RequiredPermission;
use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use MyVendor\MyProject\Resource\Page\AdminPage;

class Delete extends AdminPage
{
    public function __construct(
        private readonly AdminAuthenticatorInterface $adminAuthenticator,
        private readonly AdminRepositoryInterface $adminRepository,
        private readonly LanguageInterface $language,
    ) {
    }

    #[AdminGuard]
    #[RequiredPermission('Settings', Permission::Read)]
    public function onPost(int $id): static
    {
        $adminId = (int) $this->adminAuthenticator->getUserId();
        $admin = $this->adminRepository->findById($adminId);

        foreach ($admin->emails as $email) {
            if ($email->id === $id) {
                $this->adminRepository->store($admin->removeEmail($email));

                break;
            }
        }

        $this->renderer = new NullRenderer();
        $this->session->setFlashMessage($this->language->get('message:admin:email_deleted'));
        $this->code = StatusCode::SEE_OTHER;
        $this->headers = [ResponseHeader::LOCATION => '/admin/settings/index']; // 注意：フォームがある画面に戻るとフラッシュメッセージが表示されない

        return $this;
    }
}
