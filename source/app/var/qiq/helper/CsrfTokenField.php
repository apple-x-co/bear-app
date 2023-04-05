<?php

declare(strict_types=1);

namespace Qiq\Helper;

use Aura\Html\Helper\Input\AbstractInput;
use MyVendor\MyProject\Form\ExtendedForm;

class CsrfTokenField
{
    public function __invoke(ExtendedForm $form): AbstractInput
    {
        return $form->input('__csrf_token');
    }
}
