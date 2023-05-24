<?php

declare(strict_types=1);

namespace AppCore\Domain\WebSignature;

use AppCore\Exception\RuntimeException;

class ExpiredSignatureException extends RuntimeException
{
}
