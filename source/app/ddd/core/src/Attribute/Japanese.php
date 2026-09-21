<?php

declare(strict_types=1);

namespace AppCore\Attribute;

use Attribute;
use Ray\Di\Di\Qualifier;

#[Attribute(Attribute::TARGET_PARAMETER | Attribute::TARGET_METHOD)]
#[Qualifier]
class Japanese
{
}
