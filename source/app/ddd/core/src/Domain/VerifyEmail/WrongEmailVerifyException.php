<?php

declare(strict_types=1);

namespace AppCore\Domain\VerifyEmail;

use AppCore\Exception\RuntimeException;

class WrongEmailVerifyException extends RuntimeException
{
}
