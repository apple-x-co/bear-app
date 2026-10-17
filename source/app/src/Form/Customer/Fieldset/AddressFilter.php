<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Customer\Fieldset;

use Aura\Input\Filter;

use function ctype_digit;
use function is_array;
use function is_string;

class AddressFilter extends Filter
{
    public function __construct()
    {
        $this->setRule('zip', 'Zip is digit only.', static function (mixed $zip) {
            return is_string($zip) && ctype_digit($zip);
        });

        // 省略された（null の）住所を通さない。空文字は許可する
        foreach (['state' => 'State', 'city' => 'City', 'street' => 'Street'] as $field => $label) {
            $this->setRule($field, $label . ' is required.', static function (mixed $value) {
                return is_string($value);
            });
        }

        $this->setRule('houseType', 'House type is required.', static function (mixed $houseType) {
            return $houseType !== null && $houseType !== '';
        });

        $this->setRule('smartphones', 'Smartphones is required.', static function (mixed $smartphones) {
            return is_array($smartphones) && ! empty($smartphones);
        });
    }
}
