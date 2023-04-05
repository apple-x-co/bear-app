<?php

declare(strict_types=1);

use MyVendor\MyProject\Bootstrap;

require dirname(__DIR__, 2) . '/vendor/autoload.php';
/**
 * @psalm-suppress ArgumentTypeCoercion
 * @psalm-suppress InvalidArgument
 */
exit((new Bootstrap())('hal-app', $GLOBALS, $_SERVER));
