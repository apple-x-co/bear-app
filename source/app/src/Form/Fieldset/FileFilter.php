<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Fieldset;

use Aura\Input\Filter;

use function exif_imagetype;
use function is_array;
use function is_uploaded_file;

use const IMAGETYPE_JPEG;
use const IMAGETYPE_PNG;
use const UPLOAD_ERR_OK;

class FileFilter extends Filter
{
    public function __construct()
    {
        $this->setRule(
            'file',
            'File is required.',
            static function (mixed $value) {
                /** @var array{name: string, type: string, size: int, tmp_name: string, error: int}|null $value */

                if (! is_array($value) || $value['tmp_name'] === '') {
                    return true;
                }

                if ($value['error'] !== UPLOAD_ERR_OK) {
                    return false;
                }

                if (! is_uploaded_file($value['tmp_name'])) {
                    return false;
                }

                $imageType = exif_imagetype($value['tmp_name']);

                return $imageType === IMAGETYPE_JPEG || $imageType === IMAGETYPE_PNG;
            }
        );
    }
}
