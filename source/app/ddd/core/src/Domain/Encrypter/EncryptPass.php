<?php

declare(strict_types=1);

namespace AppCore\Domain\Encrypter;

use Attribute;
use Ray\Di\Di\Qualifier;

#[Attribute(Attribute::TARGET_PARAMETER), Qualifier]
class EncryptPass
{
}
