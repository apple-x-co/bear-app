<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Fieldset;

use Aura\Input\Filter;

use function ctype_digit;

class AddressFilter extends Filter
{
    public function __construct()
    {
        $this->setRule('zip', 'Zip is digit only.', static function (mixed $a) {
            return ctype_digit($a);
        });
    }
}
