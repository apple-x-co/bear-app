<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use AppCore\Application\Admin\VerifyAdminEmailInputData;
use AppCore\Application\Admin\VerifyAdminEmailUseCase;
use AppCore\Domain\Language\LanguageInterface;
use BEAR\Resource\NullRenderer;
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Annotation\AdminGuard;
use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use MyVendor\MyProject\Resource\Page\AdminPage;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\AuraSqlModule\Annotation\WriteConnection;

class EmailVerify extends AdminPage
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private readonly AdminAuthenticatorInterface $adminAuthenticator,
        private readonly LanguageInterface $language,
        private readonly VerifyAdminEmailUseCase $verifyAdminEmailUseCase,
    ) {
    }

    /** @SuppressWarnings(PHPMD.StaticAccess) */
    #[AdminGuard]
    #[WriteConnection]
    #[Transactional]
    public function onGet(string $signature): static
    {
        $this->verifyAdminEmailUseCase->execute(
            new VerifyAdminEmailInputData(
                (int) $this->adminAuthenticator->getUserId(),
                $signature,
            )
        );

        $this->renderer = new NullRenderer();
        $this->session->setFlashMessage($this->language->get('message:admin:email_verified'));
        $this->code = StatusCode::SEE_OTHER;
        $this->headers = [ResponseHeader::LOCATION => '/admin/settings/index']; // 注意：フォームがある画面に戻るとフラッシュメッセージが表示されない

        return $this;
    }
}
