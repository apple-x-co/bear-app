<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Aura\Html\Helper\Input\AbstractInput;
use Ray\WebFormModule\AbstractForm;
use Ray\WebFormModule\AntiCsrf;

use function array_merge;

abstract class ExtendedForm extends AbstractForm
{
    abstract protected function getFormName(): string;

    /**
     * @param string               $input
     * @param array<string, mixed> $attribs
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingNativeTypeHint
     * @phpstan-ignore-next-line
     */
    public function input($input, array $attribs = []): AbstractInput
    {
        $spec = $this->get($input);
        $spec['attribs'] = array_merge($spec['attribs'] ?? [], $attribs);

        /** @phpstan-ignore-next-line */
        return $this->helper->input($spec);
    }

    /**
     * @return array<string, mixed>
     *
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function submit(): array
    {
        $formName = $this->getFormName();

        $submit = $_POST[$formName] ?? [];

        if (isset($_POST[AntiCsrf::TOKEN_KEY])) {
            /** @psalm-suppress InvalidArrayOffset */
            $submit[AntiCsrf::TOKEN_KEY] = $_POST[AntiCsrf::TOKEN_KEY];
        }

        return $submit;
    }
}
