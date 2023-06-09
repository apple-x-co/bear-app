<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Ray\WebFormModule\SetAntiCsrfTrait;

/** @psalm-suppress PropertyNotSetInConstructor */
class AdminCodeVerifyForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private const FORM_NAME = 'codeVerify';

    public function init(): void
    {
        $this->setName(self::FORM_NAME);

        $this->setField('uuid', 'hidden');
        $this->filter->validate('uuid')->is('string');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('code', 'text')
             ->setAttribs([
                 'autofocus' => '',
                 'autocomplete' => 'one-time-code',
                 'placeholder' => '',
                 'required' => 'required',
                 'tabindex' => 1,
                 'pattern' => '^\d+$',
                 'title' => 'メールに記載されたコードを入力してください',
             ]);
        $this->filter->validate('code')->is('int');
        $this->filter->useFieldMessage('code', 'メールに記載されたコードを入力してください');

        /** @psalm-suppress UndefinedMethod */
        $this->setField('continue', 'submit')
             ->setAttribs(['tabindex' => 2]);
    }

    public function getFormName(): string
    {
        return self::FORM_NAME;
    }
}
