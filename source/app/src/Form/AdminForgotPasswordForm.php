<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Ray\WebFormModule\SetAntiCsrfTrait;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AdminForgotPasswordForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private const FORM_NAME = 'forgotPassword';

    public function init(): void
    {
        $this->setName(self::FORM_NAME);

        /** @psalm-suppress UndefinedMethod */
        $this->setField('emailAddress', 'email')
             ->setAttribs([
                 'autofocus' => '',
                 'autocomplete' => 'email',
                 'placeholder' => '',
                 'required' => 'required',
                 'tabindex' => 1,
             ]);
        $this->filter->validate('emailAddress')->is('email');
        $this->filter->useFieldMessage('emailAddress', 'Email address must be email only.');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('continue', 'submit')
             ->setAttribs(['tabindex' => 2]);
    }

    public function getFormName(): string
    {
        return self::FORM_NAME;
    }
}
