<?php

declare(strict_types=1);

namespace Qiq\Helper;

use Aura\Input\Fieldset;

use function array_merge;

class AdminFieldsetError extends Helper
{
    public function __invoke(Fieldset $fieldset, string $input, string $tag = 'span', array $attr = []): string
    {
        if ($fieldset->isSuccess()) {
            return '';
        }

        $messages = $fieldset->getMessages($input);
        if (empty($messages)) {
            return '';
        }

        $defaultAttrs = ['class' => 'block text-sm text-rose-500 italic'];

        return $this->fullTag($tag, array_merge($defaultAttrs, $attr), $this->escape->h($messages[0]));
    }
}
