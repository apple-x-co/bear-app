<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use AppCore\Application\Admin\CreateAdminInputData;
use AppCore\Application\Admin\CreateAdminUseCase;
use AppCore\Application\Admin\GetJoinedAdminInputData;
use AppCore\Application\Admin\GetJoinedAdminUseCase;
use BEAR\Resource\NullRenderer;
use Koriym\HttpConstants\ResponseHeader;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Form\ExtendedForm;
use MyVendor\MyProject\Input\Admin\SignUpInput;
use MyVendor\MyProject\Resource\Page\AdminPage;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;
use Throwable;

use function assert;

class SignUp extends AdminPage
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        private readonly CreateAdminUseCase $createAdminUseCase,
        private readonly GetJoinedAdminUseCase $getJoinedAdminUseCase,
        #[Named('admin_sign_up_form')] protected readonly FormInterface $form,
    ) {
        $this->body['form'] = $this->form;
    }

    public function onGet(string $signature): static
    {
        try {
            $this->getJoinedAdminUseCase->execute(
                new GetJoinedAdminInputData($signature),
            );
        } catch (Throwable) {
            $this->session->set('error:message', 'message:admin:sign_up:decrypt_error');
            $this->session->set('error:returnName', 'Join');
            $this->session->set('error:returnUrl', '/admin/join');
            $this->renderer = new NullRenderer();
            $this->code = StatusCode::SEE_OTHER;
            $this->headers = [ResponseHeader::LOCATION => '/admin/error'];

            return $this;
        }

        assert($this->form instanceof ExtendedForm);
        $this->form->fill(['signature' => $signature]);

        return $this;
    }

    /**
     * @FormValidation()
     * @Transactional()
     */
    public function onPost(SignUpInput $signUp): static
    {
        $this->createAdminUseCase->execute(
            new CreateAdminInputData(
                $signUp->username,
                $signUp->displayName,
                $signUp->password,
                $signUp->signature,
            )
        );

        $this->renderer = new NullRenderer();
        $this->code = StatusCode::SEE_OTHER;
        $this->headers = [ResponseHeader::LOCATION => '/admin/login']; // 注意：フォームがある画面に戻るとフラッシュメッセージが表示されない

        return $this;
    }

    public function onPostValidationFailed(): static
    {
        return $this;
    }
}
