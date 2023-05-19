<?php

declare(strict_types=1);

namespace AppCore\Domain\ResetPassword;

use AppCore\Exception\RuntimeException;

class InvalidSignatureException extends RuntimeException
{
}
