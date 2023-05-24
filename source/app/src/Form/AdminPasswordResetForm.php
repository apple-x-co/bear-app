<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Ray\WebFormModule\SetAntiCsrfTrait;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AdminPasswordResetForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private const FORM_NAME = 'resetPassword';

    public function init(): void
    {
        $this->setName(self::FORM_NAME);

        /** @psalm-suppress UndefinedMethod */
        $this->setField('password', 'password')
             ->setAttribs([
                 'autocomplete' => 'current-password',
                 'placeholder' => '',
                 'required' => 'required',
                 'tabindex' => 2,
             ]);
        $this->filter->validate('password')->is('alnum');
        $this->filter->useFieldMessage('password', 'Name must be alphabetic only.');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('continue', 'submit')
             ->setAttribs(['tabindex' => 3]);

        $this->setField('signature', 'hidden');
    }

    public function getFormName(): string
    {
        return self::FORM_NAME;
    }
}
