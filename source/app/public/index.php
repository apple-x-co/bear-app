<?php

declare(strict_types=1);

use MyVendor\MyProject\Bootstrap;

session_name('bear-app');

require dirname(__DIR__) . '/autoload.php';

$environment = getenv('APP_CONTEXT') ?: (PHP_SAPI === 'cli-server' ? 'dev' : 'prod');
$apiType = str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') ? 'hal-api-app' : 'html-app';
$context = ($environment === 'prod' ? 'prod-' : '') . $apiType;

exit((new Bootstrap())($context, $GLOBALS, $_SERVER));

//exit((new Bootstrap())(PHP_SAPI === 'cli-server' ? 'hal-app' : 'prod-hal-app', $GLOBALS, $_SERVER)); // old
