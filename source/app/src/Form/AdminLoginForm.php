<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Ray\WebFormModule\SetAntiCsrfTrait;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AdminLoginForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private const FORM_NAME = 'loginUser';

    public function init(): void
    {
        $this->setName(self::FORM_NAME);

        /** @psalm-suppress UndefinedMethod */
        $this->setField('username', 'text')
             ->setAttribs([
                 'autofocus' => '',
                 'autocomplete' => 'email',
                 'placeholder' => 'username',
                 'required' => 'required',
                 'tabindex' => 1,
             ]);
        $this->filter->validate('username')->is('alnum');
        $this->filter->useFieldMessage('username', 'Name must be alphabetic only.');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('password', 'password')
             ->setAttribs([
                 'autocomplete' => 'current-password',
                 'placeholder' => 'password',
                 'required' => 'required',
                 'tabindex' => 2,
             ]);
        $this->filter->validate('password')->is('alnum');
        $this->filter->useFieldMessage('password', 'Name must be alphabetic only.');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('login', 'submit')
             ->setAttribs(['tabindex' => 4]);
    }

    public function getFormName(): string
    {
        return self::FORM_NAME;
    }
}
