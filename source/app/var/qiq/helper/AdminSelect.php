<?php

declare(strict_types=1);

namespace Qiq\Helper;

use Aura\Html\Helper\Input\AbstractInput;
use Aura\Input\Fieldset;
use MyVendor\MyProject\Form\ExtendedForm;

use function array_merge;

class AdminSelect extends Helper
{
    public function __invoke(
        ExtendedForm $form,
        string $input,
        Fieldset $fieldset = null,
        array $attribs = []
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => 'rounded focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500'];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }
}
