<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

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
                 'placeholder' => '',
                 'required' => 'required',
                 'tabindex' => 1,
                 'title' => '有効なユーザー名を入力してください',
             ]);
        $this->filter->validate('username')->is('alnum');
        $this->filter->useFieldMessage('username', '有効なユーザー名を入力してください');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('password', 'password')
             ->setAttribs([
                 'autocomplete' => 'current-password',
                 'placeholder' => '',
                 'required' => 'required',
                 'tabindex' => 2,
                 'title' => '有効なパスワードを入力してください',
             ]);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('password')
            ->is('string')
            ->is('regex', '/^[A-Za-z0-9!@#$%^&*]+$/i');
        $this->filter->useFieldMessage('password', '有効なパスワードを入力してください');

        $this->setField('remember', 'checkbox')
             ->setAttribs([
                 'tabindex' => 3,
                 'value' => 'yes',
                 'label' => 'YES',
                 'value_unchecked' => 'no',
             ]);

        /** @psalm-suppress UndefinedMethod */
        $this->setField('login', 'submit')
             ->setAttribs(['tabindex' => 5]);
    }

    public function getFormName(): string
    {
        return '';
    }
}
