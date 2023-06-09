<?php

declare(strict_types=1);

namespace AppCore\Domain\Mail;

use Attribute;
use Ray\Di\Di\Qualifier;

#[Attribute(Attribute::TARGET_PARAMETER), Qualifier]
class EmailDir
{
}
