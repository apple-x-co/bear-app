<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\User;

use MyVendor\MyProject\Annotation\UserLogin;
use MyVendor\MyProject\Auth\AuthenticationException;
use MyVendor\MyProject\InputQuery\User\LoginUserInput;
use MyVendor\MyProject\Resource\Page\UserPage;
use Ray\Di\Di\Named;
use Ray\InputQuery\Attribute\Input;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

class Login extends UserPage
{
    public function __construct(
        #[Named('user_login_form')] protected readonly FormInterface $form,
    ) {
        $this->body['form'] = $this->form;
    }

    public function onGet(): static
    {
        return $this;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @FormValidation()
     */
    #[UserLogin]
    public function onPost(#[Input] LoginUserInput $input): static
    {
        // login success !!

        return $this;
    }

    public function onPostValidationFailed(): static
    {
        return $this;
    }

    public function onPostAuthenticationFailed(AuthenticationException $authException): static
    {
        $this->body['authException'] = $authException;

        return $this;
    }
}
