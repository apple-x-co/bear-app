<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form\Fixture;

use Aura\Input\Filter;

use function is_string;

class NestedDemoGeoFilter extends Filter
{
    public function __construct()
    {
        $this->setRule('lat', 'Lat is required.', static function (mixed $lat) {
            return is_string($lat) && $lat !== '';
        });
    }
}
