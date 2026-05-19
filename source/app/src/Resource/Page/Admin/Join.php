<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use AppCore\Application\Admin\SendAdminSignUpCodeInputData;
use AppCore\Application\Admin\SendAdminSignUpCodeUseCase;
use AppCore\Domain\Captcha\CaptchaException;
use BEAR\Resource\NullRenderer;
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Annotation\CloudflareTurnstile;
use MyVendor\MyProject\Annotation\RateLimiter;
use MyVendor\MyProject\InputQuery\Admin\JoinInput;
use MyVendor\MyProject\Resource\Page\BaseAdminPage;
use Ray\Di\Di\Named;
use Ray\InputQuery\Attribute\Input;
use Ray\WebFormModule\Annotation\CsrfProtection;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

/** @SuppressWarnings("PHPMD.CouplingBetweenObjects") */
class Join extends BaseAdminPage
{
    /** @SuppressWarnings("PHPMD.LongVariable") */
    public function __construct(
        protected readonly SendAdminSignUpCodeUseCase $sendAdminSignUpCodeUseCase,
        #[Named('admin_join_form')]
        protected readonly FormInterface $form,
    ) {
        $this->body['form'] = $this->form;
    }

    public function onGet(): static
    {
        return $this;
    }

    #[FormValidation]
    #[CsrfProtection]
    #[CloudflareTurnstile]
    #[RateLimiter]
    public function onPost(
        #[Input]
        JoinInput $input,
    ): static {
        $outputData = $this->sendAdminSignUpCodeUseCase->execute(
            new SendAdminSignUpCodeInputData(
                $input->emailAddress,
            ),
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
     * Callback from CloudflareTurnstileVerification
     *
     * @SuppressWarnings("PHPMD.UnusedFormalParameter")
     */
    public function onCfTurnstileFailed(
        JoinInput $input,
        CaptchaException $captchaException,
    ): static {
        $this->body['captchaError'] = true;

        return $this;
    }
}
