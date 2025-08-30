<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Admin\Fieldset;

use Aura\Input\Filter;

use function ctype_digit;
use function is_array;

class AddressFilter extends Filter
{
    public function __construct()
    {
        $this->setRule('zip', 'Zip is digit only.', static function (mixed $zip) {
            return ctype_digit($zip);
        });

        $this->setRule('houseType', 'House type is required.', static function (mixed $houseType) {
            return $houseType !== null && $houseType !== '';
        });

        $this->setRule('smartphones', 'Smartphones is required.', static function (mixed $smartphones) {
            return is_array($smartphones) && ! empty($smartphones);
        });
    }
}
