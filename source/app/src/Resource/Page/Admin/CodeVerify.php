<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use AppCore\Infrastructure\Query\VerificationCodeCommandInterface;
use AppCore\Infrastructure\Query\VerificationCodeQueryInterface;
use DateTimeImmutable;
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Form\ExtendedForm;
use MyVendor\MyProject\Input\Admin\CodeVerifyInput;
use MyVendor\MyProject\Resource\Page\AdminPage;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

use function assert;

class CodeVerify extends AdminPage
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        #[Named('admin_code_verify_form')] protected readonly FormInterface $form,
        private readonly VerificationCodeCommandInterface $verificationCodeCommand,
        private readonly VerificationCodeQueryInterface $verificationCodeQuery,
    ) {
        $this->body['form'] = $this->form;
    }

    public function onGet(string $uuid): static
    {
        $verificationCode = $this->verificationCodeQuery->itemByUuid($uuid);
        if ($verificationCode === null) {
            $this->renderer = null;
            $this->code = StatusCode::SEE_OTHER;
            $this->headers = [ResponseHeader::LOCATION => '/admin/login']; // 注意：フォームがある画面に戻るとフラッシュメッセージが表示されない

            return $this;
        }

        assert($this->form instanceof ExtendedForm);
        $this->form->fill(['uuid' => $uuid]);

        return $this;
    }

    /**
     * @FormValidation()
     * @Transactional()
     */
    public function onPost(CodeVerifyInput $codeVerify): static
    {
        $this->renderer = null;
        $this->code = StatusCode::SEE_OTHER;

        $verificationCode = $this->verificationCodeQuery->itemByUuid($codeVerify->uuid);
        if ($verificationCode === null || $verificationCode->code !== $codeVerify->code) {
            $this->headers = [ResponseHeader::LOCATION => '/admin/login']; // 注意：フォームがある画面に戻るとフラッシュメッセージが表示されない

            return $this;
        }

        $this->verificationCodeCommand->verified($verificationCode->id, new DateTimeImmutable());
        $this->headers = [ResponseHeader::LOCATION => $verificationCode->url]; // 注意：フォームがある画面に戻るとフラッシュメッセージが表示されない

        return $this;
    }

    public function onPostValidationFailed(): static
    {
        return $this;
    }
}
