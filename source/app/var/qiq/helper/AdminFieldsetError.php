<?php

declare(strict_types=1);

namespace Qiq\Helper;

use MyVendor\MyProject\Form\ExtendedFieldset;

use function array_merge;

class AdminFieldsetError extends Helper
{
    public function __invoke(ExtendedFieldset $fieldset, string $input, string $tag = 'span', array $attr = []): string
    {
        $errorMessages = $fieldset->error($input);
        if (empty($errorMessages)) {
            return '';
        }

        $defaultAttrs = ['class' => 'block text-sm text-rose-500 italic'];

        return $this->fullTag($tag, array_merge($defaultAttrs, $attr), $this->escape->h($errorMessages[0]));
    }
}
