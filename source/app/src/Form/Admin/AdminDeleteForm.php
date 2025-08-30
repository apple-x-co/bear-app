<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Admin;

use MyVendor\MyProject\Form\ExtendedForm;
use Ray\WebFormModule\SetAntiCsrfTrait;

/** @psalm-suppress PropertyNotSetInConstructor */
class AdminDeleteForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    public function init(): void
    {
        /** @psalm-suppress UndefinedMethod */
        $this->setField('continue', 'submit');
    }
}
