<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Aura\Input\Builder;
use MyVendor\MyProject\Form\Fieldset\FileFieldset;
use MyVendor\MyProject\Form\Fieldset\FileFilter;
use Ray\WebFormModule\SetAntiCsrfTrait;
use stdClass;

use function is_array;

/**
 * @property FileFieldset $fileset
 * @property string $mode
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AdminUpload2DemoForm extends ExtendedForm
{
    use SetAntiCsrfTrait;

    private const FORM_NAME = 'upload2Demo';

    public function init(): void
    {
        $this->setName(self::FORM_NAME);

        $this->builder = new Builder([
            'fileset' => static function () {
                return new FileFieldset(
                    new Builder(),
                    new FileFilter(),
                );
            },
        ]);

        $fileset = $this->setFieldset('fileset', 'fileset');
        /** @psalm-suppress UndefinedMethod */
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('fileset')
            ->is('callback', static function (stdClass $subject, string $field) use ($fileset) {
                $value = $subject->$field;
                $file = $value['file'] ?? null;
                $tmpName = $value['tmpName'] ?? null;
                if (($file === null || (is_array($file) && $file['tmp_name'] === '')) && ($tmpName === '' || $tmpName === null)) {
                    return false;
                }

                return (new FileFilter())->values($fileset);
            });

        /** @psalm-suppress UndefinedMethod */
        $this->setField('mode', 'submit')
             ->setAttribs(['tabindex' => 99]);
    }

    public function getFormName(): string
    {
        return self::FORM_NAME;
    }
}
