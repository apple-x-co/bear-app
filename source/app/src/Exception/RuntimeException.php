<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Exception;

use RuntimeException as PhpRuntimeException;

/**
 * @SuppressWarnings("PHPMD.NumberOfChildren")
 * @psalm-suppress ClassMustBeFinal 個別の例外の基底クラスとして継承して使うため final にしない
 */
class RuntimeException extends PhpRuntimeException
{
}
