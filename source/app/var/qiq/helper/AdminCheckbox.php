<?php

declare(strict_types=1);

namespace Qiq\Helper;

use Aura\Html\Helper\Input\AbstractInput;
use Aura\Input\Fieldset;
use MyVendor\MyProject\Form\ExtendedForm;

use function array_merge;

class AdminCheckbox extends Helper
{
    public function __invoke(
        ExtendedForm $form,
        string $input,
        Fieldset $fieldset = null,
        array $attribs = []
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => '', 'label_class' => 'bb'];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }
}
