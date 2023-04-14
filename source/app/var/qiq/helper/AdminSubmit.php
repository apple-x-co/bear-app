<?php

declare(strict_types=1);

namespace Qiq\Helper;

use Aura\Html\Helper\Input\AbstractInput;
use Aura\Input\Fieldset;
use MyVendor\MyProject\Form\ExtendedForm;

class AdminSubmit extends Helper
{
    public function __invoke(
        ExtendedForm $form,
        string $input,
        Fieldset $fieldset = null,
        array $attribs = []
    ): AbstractInput
    {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        if (isset($attribs['value'])) {
            $spec['value'] = $attribs['value'];
            unset($attribs['value']);
        }

        $defaultAttribs = [
            'value' => 'Submit',
            'class' => 'py-2 px-3 bg-sky-500 text-white text-sm font-sans font-bold tracking-wider rounded-md shadow-lg shadow-sky-500/50 focus:outline-none disabled:text-white disabled:bg-slate-200 disabled:shadow-none',
        ];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }
}
