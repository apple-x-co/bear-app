<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Ray\WebFormModule\SetAntiCsrfTrait;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AdminDeleteForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private const FORM_NAME = 'delete';

    public function init(): void
    {
        $this->setName(self::FORM_NAME);

        /** @psalm-suppress UndefinedMethod */
        $this->setField('continue', 'submit')
             ->setAttribs(['tabindex' => 1]);
    }

    public function getFormName(): string
    {
        return self::FORM_NAME;
    }
}
