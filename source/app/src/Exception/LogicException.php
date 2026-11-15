<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Exception;

use LogicException as PhpLogicException;

/** @psalm-suppress ClassMustBeFinal 個別の例外の基底クラスとして継承して使うため final にしない */
class LogicException extends PhpLogicException
{
}
