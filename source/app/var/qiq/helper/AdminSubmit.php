<?php

declare(strict_types=1);

namespace Qiq\Helper;

use Aura\Html\Helper\Input\AbstractInput;
use MyVendor\MyProject\Form\ExtendedForm;

class AdminSubmit extends Helper
{
    public function __invoke(ExtendedForm $form, string $input, array $attribs = []): AbstractInput
    {
        $defaultAttribs = [
            'value' => 'Submit',
            'class' => 'py-2 px-3 bg-sky-500 text-white text-sm font-sans font-bold tracking-wider rounded-md shadow-lg shadow-sky-500/50 focus:outline-none disabled:text-white disabled:bg-slate-200 disabled:shadow-none',
        ];

        return $form->input($input, array_merge($defaultAttribs, $attribs));
    }
}
