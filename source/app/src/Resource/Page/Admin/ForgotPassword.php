<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use AppCore\Application\Admin\ForgotAdminPasswordInputData;
use AppCore\Application\Admin\ForgotAdminPasswordUseCase;
use BEAR\Resource\NullRenderer;
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Annotation\GoogleRecaptchaV2;
use MyVendor\MyProject\Annotation\RateLimiter;
use MyVendor\MyProject\Captcha\RecaptchaException;
use MyVendor\MyProject\Input\Admin\ForgotPasswordInput;
use MyVendor\MyProject\Resource\Page\AdminPage;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

class ForgotPassword extends AdminPage
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private readonly ForgotAdminPasswordUseCase $forgotAdminPasswordUseCase,
        #[Named('admin_forgot_password_form')] protected readonly FormInterface $form,
    ) {
        $this->body['form'] = $this->form;
    }

    public function onGet(): static
    {
        return $this;
    }

    /**
     * @FormValidation()
     * @Transactional()
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    #[GoogleRecaptchaV2]
    #[RateLimiter]
    public function onPost(ForgotPasswordInput $forgotPassword): static
    {
        $outputData = $this->forgotAdminPasswordUseCase->execute(
            new ForgotAdminPasswordInputData($forgotPassword->emailAddress),
        );

        $this->renderer = new NullRenderer();
        $this->code = StatusCode::SEE_OTHER;
        $this->headers = [ResponseHeader::LOCATION => $outputData->redirectUrl]; // 注意：フォームがある画面に戻るとフラッシュメッセージが表示されない

        return $this;
    }

    public function onPostValidationFailed(): static
    {
        return $this;
    }

    /**
     * @param array<RecaptchaException> $recaptchaExceptions
     */
    public function onPostGoogleRecaptchaV2Failed(array $recaptchaExceptions): static
    {
        $this->body['recaptchaError'] = ! empty($recaptchaExceptions);

        return $this;
    }
}
