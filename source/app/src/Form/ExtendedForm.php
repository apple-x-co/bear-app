<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Aura\Html\Helper\Input\AbstractInput;
use Ray\WebFormModule\AbstractForm;
use Ray\WebFormModule\AntiCsrf;
use Ray\WebFormModule\SubmitInterface;

use function array_merge;
use function is_array;

abstract class ExtendedForm extends AbstractForm implements SubmitInterface
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

        if ($spec['type'] === 'file' && is_array($spec['value']) && isset($spec['value']['tmp_name'])) {
            $spec['value'] = null;
        }

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

        /** @var array<string, mixed> $posts */
        $posts = $_POST[$formName] ?? [];

        if (isset($_POST[AntiCsrf::TOKEN_KEY])) {
            /** @psalm-suppress InvalidArrayOffset */
            $posts[AntiCsrf::TOKEN_KEY] = $_POST[AntiCsrf::TOKEN_KEY];
        }

        if (isset($_FILES[$formName])) {
            $files = (new NormalizeFiles())($_FILES);
            if (! empty($files)) {
                $posts = array_merge($posts, $files[$formName]);
            }
        }

        return $posts;
    }
}
