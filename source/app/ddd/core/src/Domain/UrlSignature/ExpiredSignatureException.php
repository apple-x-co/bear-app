<?php

declare(strict_types=1);

namespace AppCore\Domain\UrlSignature;

use AppCore\Exception\RuntimeException;

final class ExpiredSignatureException extends RuntimeException
{
}
