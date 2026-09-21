<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Customer;

use MyVendor\MyProject\Form\ExtendedForm;
use Ray\WebFormModule\SetAntiCsrfTrait;

/** @psalm-suppress PropertyNotSetInConstructor */
class UserLoginForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    public function init(): void
    {
        /** @psalm-suppress UndefinedMethod */
        $this->setField('username', 'text')
             ->setAttribs([
                 'autofocus' => '',
                 'autocomplete' => 'username',
                 'placeholder' => 'public.login.username',
                 'required' => 'required',
                 'title' => 'public.login.invalid_username',
             ]);
        $this->filter->validate('username')->is('alnum');
        $this->filter->useFieldMessage('username', 'public.login.invalid_username');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('password', 'password')
             ->setAttribs([
                 'autocomplete' => 'current-password',
                 'placeholder' => 'public.login.password',
                 'required' => 'required',
                 'title' => 'public.login.invalid_password',
             ]);
        $this->filter->validate('password')->is('string');
        /** @psalm-suppress TooManyArguments */
        $this->filter->validate('password')->is('regex', '/^[A-Za-z0-9!@#$%^&*]+$/i');
        $this->filter->useFieldMessage('password', 'public.login.invalid_password');

        $this->setField('remember', 'checkbox')
             ->setAttribs([
                 'value' => 'yes',
                 'label' => 'YES',
                 'value_unchecked' => 'no',
             ]);

        /** @psalm-suppress UndefinedMethod */
        $this->setField('login', 'submit')
            ->setAttribs(['value' => 'public.login.submit']);
    }
}
