<?php

declare(strict_types=1);

use MyVendor\MyProject\Bootstrap;

require dirname(__DIR__) . '/autoload.php';

$environment = getenv('APP_CONTEXT') ?: (PHP_SAPI === 'cli-server' ? 'dev' : 'prod');
$apiType = 'cli-command-app';
$context = ($environment === 'prod' ? 'prod-' : '') . $apiType;

exit((new Bootstrap())($context, $GLOBALS, $_SERVER));

// exit((new Bootstrap())('cli-command-app', $GLOBALS, $_SERVER));
