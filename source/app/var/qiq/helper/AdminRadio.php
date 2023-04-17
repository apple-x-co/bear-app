<?php

declare(strict_types=1);

namespace Qiq\Helper;

use Aura\Html\Helper\Input\AbstractInput;
use MyVendor\MyProject\Form\ExtendedFieldset;
use MyVendor\MyProject\Form\ExtendedForm;

use function array_merge;

class AdminRadio extends Helper
{
    public function __invoke(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset $fieldset = null,
        array $attribs = []
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => ''];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }
}
