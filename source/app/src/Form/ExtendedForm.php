<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Aura\Html\Helper\Input\AbstractInput as InputHelper;
use Aura\Input\AbstractInput;
use Aura\Input\Collection;
use Aura\Input\Exception\NoSuchInput;
use Aura\Input\Fieldset;
use Ray\WebFormModule\AbstractForm;
use Ray\WebFormModule\SubmitInterface;

use function array_key_exists;
use function array_merge;
use function array_values;
use function is_array;
use function preg_match_all;

/**
 * Fieldset / Collection の入れ子に対応したフォーム基底クラス
 *
 * エラーと入力のキーは HTML の name と同じパス表記にする。
 * 例: `note`, `home[zip]`, `deliveries[0][zip]`
 *
 * @psalm-suppress PropertyNotSetInConstructor
 * @SuppressWarnings("PHPMD.NumberOfChildren")
 */
abstract class ExtendedForm extends AbstractForm implements SubmitInterface
{
    /** @param array<string, callable> $fieldsetMap Fieldset 種別 => Fieldset を返すファクトリ */
    protected function useFieldsets(array $fieldsetMap): void
    {
        $this->builder = new ExtendedBuilder($fieldsetMap);
    }

    /**
     * @return array<string, mixed>
     *
     * @SuppressWarnings("PHPMD.Superglobals")
     */
    public function submit(): array
    {
        /** @var array<string, mixed> $post */
        $post = $_POST;

        return $post;
    }

    /**
     * Fieldset / Collection には配列のときだけ渡す
     *
     * @param array<array-key, mixed> $data
     */
    public function fill(array $data): void
    {
        foreach ($this->inputs as $key => $input) {
            if (! array_key_exists($key, $data)) {
                continue;
            }

            $value = $data[$key];
            if (($input instanceof Fieldset || $input instanceof Collection) && ! is_array($value)) {
                continue;
            }

            $input->fill($value);
        }
    }

    /**
     * トップレベルに加えて、入れ子の Fieldset / Collection も検証する
     *
     * @param array<string, mixed> $data
     */
    public function apply(array $data): bool
    {
        $this->errorMessages = null;

        $topLevelPassed = parent::apply($data);
        $nestedPassed = $this->filterChildren($this);

        return $this->success = ($topLevelPassed && $nestedPassed);
    }

    /** @return array<string, array<int, string>> */
    public function getFailureMessages(): array
    {
        $messages = parent::getFailureMessages();
        $this->collectChildMessages($this, '', $messages);

        return $messages;
    }

    public function error(string $input): string
    {
        $this->errorMessages ??= $this->getFailureMessages();

        return $this->errorMessages[$input][0] ?? '';
    }

    /** @throws NoSuchInput */
    public function input(string $input): string
    {
        return (string) $this->widget($input);
    }

    /**
     * @param string|array<string, mixed> $nameOrSpec 入力名（`home[zip]` のようなパスも可）、または Field の描画用配列
     * @param array<string, mixed>        $attribs
     *
     * @throws NoSuchInput
     */
    public function widget(string|array $nameOrSpec, array $attribs = []): InputHelper
    {
        $spec = is_array($nameOrSpec) ? $nameOrSpec : $this->resolveField($nameOrSpec);
        $spec['attribs'] = array_merge($spec['attribs'] ?? [], $attribs);

        return $this->helper->input($spec); // @phpstan-ignore-line
    }

    /**
     * 子孫すべての Fieldset を検証する（途中で止めず、全エラーを集める）
     */
    private function filterChildren(Fieldset $parent): bool
    {
        $passed = true;
        foreach ($this->childFieldsets($parent) as $fieldset) {
            $selfPassed = $fieldset->filter();
            $childrenPassed = $this->filterChildren($fieldset);
            $passed = $passed && $selfPassed && $childrenPassed;
        }

        return $passed;
    }

    /** @param array<string, array<int, string>> $messages */
    private function collectChildMessages(Fieldset $parent, string $prefix, array &$messages): void
    {
        foreach ($parent->getInputNames() as $name) {
            $input = $parent->getInput($name);
            $path = $prefix === '' ? $name : $prefix . '[' . $name . ']';

            foreach ($this->fieldsetsOf($input) as $key => $fieldset) {
                $fieldsetPath = $input instanceof Collection ? $path . '[' . $key . ']' : $path;
                /** @var array<string, array<int, string>> $fieldsetMessages */
                $fieldsetMessages = $fieldset->getMessages();
                foreach ($fieldsetMessages as $field => $fieldMessages) {
                    $messages[$fieldsetPath . '[' . $field . ']'] = array_values($fieldMessages);
                }

                $this->collectChildMessages($fieldset, $fieldsetPath, $messages);
            }
        }
    }

    /**
     * 直下の子 Fieldset（Collection は各行）を返す
     *
     * @return array<array-key, Fieldset>
     */
    private function childFieldsets(Fieldset $parent): array
    {
        $fieldsets = [];
        foreach ($parent->getInputNames() as $name) {
            foreach ($this->fieldsetsOf($parent->getInput($name)) as $fieldset) {
                $fieldsets[] = $fieldset;
            }
        }

        return $fieldsets;
    }

    /**
     * Fieldset なら自身を 1 件、Collection なら行ごとに、それ以外は空で返す
     *
     * @return array<array-key, Fieldset>
     */
    private function fieldsetsOf(AbstractInput $input): array
    {
        if ($input instanceof Fieldset) {
            return [$input];
        }

        if (! $input instanceof Collection) {
            return [];
        }

        $fieldsets = [];
        foreach ($input->getKeys() as $key) {
            $fieldsets[$key] = $input[$key];
        }

        return $fieldsets;
    }

    /**
     * `home[zip]` / `deliveries[0][zip]` を Field の描画用配列に解決する
     *
     * @return array<string, mixed>
     *
     * @throws NoSuchInput
     */
    private function resolveField(string $path): array
    {
        preg_match_all('/[^\[\]]+/', $path, $matches);

        $node = $this;
        foreach ($matches[0] as $segment) {
            if ($node instanceof Collection) {
                if (! isset($node[$segment])) {
                    throw new NoSuchInput($path);
                }

                $node = $node[$segment];
                continue;
            }

            if (! $node instanceof Fieldset) {
                throw new NoSuchInput($path);
            }

            $node = $node->get($segment);
        }

        if (! is_array($node)) {
            throw new NoSuchInput($path);
        }

        return $node;
    }
}
