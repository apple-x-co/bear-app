<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use stdClass;

use function exif_imagetype;

use const IMAGETYPE_JPEG;
use const IMAGETYPE_PNG;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class AdminUploadDemoForm extends ExtendedForm
{
    private const FORM_NAME = 'uploadDemo';

    public function init(): void
    {
        $this->setName(self::FORM_NAME);

        /** @psalm-suppress UndefinedMethod */
        $this->setField('file', 'file')
             ->setAttribs([
                 'required' => 'required',
                 'tabindex' => 1,
             ]);
        /** @psalm-suppress UndefinedMethod */
        /** @psalm-suppress TooManyArguments */
        $this->filter
            ->validate('file')
            ->is('upload')
            ->is('callback', static function (stdClass $subject, string $field) {
                $array = $subject->$field;

                if (! isset($array['tmp_name']) || $array['tmp_name'] === '') {
                    return false;
                }

                $imageType = exif_imagetype($array['tmp_name']);

                return $imageType === IMAGETYPE_JPEG || $imageType === IMAGETYPE_PNG;
            });

        /** @psalm-suppress UndefinedMethod */
        $this->setField('submit', 'submit')
             ->setAttribs(['tabindex' => 2]);
    }

    public function getFormName(): string
    {
        return self::FORM_NAME;
    }
}
