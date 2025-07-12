<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Ray\WebFormModule\SetAntiCsrfTrait;

/** @psalm-suppress PropertyNotSetInConstructor */
class AdminPasswordConfirmForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    public function init(): void
    {
        /** @psalm-suppress UndefinedMethod */
        $this->setField('password', 'password')
             ->setAttribs([
                 'autofocus' => '',
                 'autocomplete' => 'current-password',
                 'placeholder' => '',
                 'required' => 'required',
                 'title' => '有効なパスワードを入力してください',
             ]);
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('password')
            ->is('string')
            ->is('regex', '/^[A-Za-z0-9!@#$%^&*]+$/i');
        $this->filter->useFieldMessage('password', '有効なパスワードを入力してください');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('continue', 'submit');
    }

    public function getFormName(): string
    {
        return '';
    }
}
