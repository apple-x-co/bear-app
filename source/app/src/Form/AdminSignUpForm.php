<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Ray\WebFormModule\SetAntiCsrfTrait;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AdminSignUpForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private const FORM_NAME = 'signUp';

    public function init(): void
    {
        $this->setName(self::FORM_NAME);

        /** @psalm-suppress UndefinedMethod */
        $this->setField('displayName', 'text')
             ->setAttribs([
                 'autofocus' => '',
                 'autocomplete' => '',
                 'placeholder' => '',
                 'required' => 'required',
                 'tabindex' => 1,
             ]);
        $this->filter->validate('displayName')->is('string');
        $this->filter->useFieldMessage('displayName', 'Display name must be string.');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('username', 'text')
             ->setAttribs([
                 'autofocus' => '',
                 'autocomplete' => '',
                 'placeholder' => '',
                 'required' => 'required',
                 'tabindex' => 2,
             ]);
        $this->filter->validate('username')->is('string');
        $this->filter->useFieldMessage('username', 'Username must be string.');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('password', 'password')
             ->setAttribs([
                 'autocomplete' => 'current-password',
                 'placeholder' => '',
                 'required' => 'required',
                 'tabindex' => 3,
             ]);
        $this->filter->validate('password')->is('alnum');
        $this->filter->useFieldMessage('password', 'Name must be alphabetic only.');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('continue', 'submit')
             ->setAttribs(['tabindex' => 4]);

        $this->setField('signature', 'hidden');
    }

    public function getFormName(): string
    {
        return self::FORM_NAME;
    }
}
