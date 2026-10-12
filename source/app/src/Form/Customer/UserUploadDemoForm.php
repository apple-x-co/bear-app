<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Customer;

use MyVendor\MyProject\Form\ExtendedForm;

/** @psalm-suppress PropertyNotSetInConstructor */
class UserUploadDemoForm extends ExtendedForm
{
    public function init(): void
    {
        /** @psalm-suppress UndefinedMethod */
        $this->setField('file', 'file')
             ->setAttribs(['required' => 'required']);

        // NOTE: $_POSTS[] にアップロードファイルは含まれないのでここでは検証しない

        /** @psalm-suppress UndefinedMethod */
        $this->setField('submit', 'submit');
    }
}
