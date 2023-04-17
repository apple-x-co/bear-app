<?php

declare(strict_types=1);

namespace Qiq\Helper;

use Aura\Html\Helper\Input\AbstractInput;
use MyVendor\MyProject\Form\ExtendedFieldset;
use MyVendor\MyProject\Form\ExtendedForm;

use function array_merge;

class AdminTextarea extends Helper
{
    public function __invoke(
        ExtendedForm $form,
        string $input,
        ExtendedFieldset $fieldset = null,
        array $attribs = []
    ): AbstractInput {
        $spec = $fieldset === null ? $form->get($input) : $fieldset->get($input);

        $defaultAttribs = ['class' => 'rounded w-full placeholder:text-slate-500 placeholder:font-thin focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500 leading-6 h-40'];

        return $form->widget($spec, array_merge($defaultAttribs, $attribs));
    }
}
