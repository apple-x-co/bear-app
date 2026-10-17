<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Fixture;

use Aura\Input\Filter;

use function ctype_digit;
use function is_string;

class NestedDemoZipFilter extends Filter
{
    public function __construct()
    {
        $this->setRule('zip', 'Zip is digit only.', static function (mixed $zip) {
            return is_string($zip) && ctype_digit($zip);
        });
    }
}
