<?php

declare(strict_types=1);

namespace Qiq\Helper;

use MyVendor\MyProject\Form\ExtendedForm;

use function array_merge;

class AdminFormError extends Helper
{
    public function __invoke(ExtendedForm $form, string $input, string $tag = 'span', array $attr = []): string
    {
        $message = $form->error($input);
        if ($message === '') {
            return '';
        }

        $defaultAttrs = ['class' => 'block text-sm text-rose-500 italic'];

        return $this->fullTag($tag, array_merge($defaultAttrs, $attr), $this->escape->h($message));
    }
}
