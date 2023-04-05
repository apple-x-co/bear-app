<?php

declare(strict_types=1);

use MyVendor\MyProject\Bootstrap;

session_name('bear-app');

require dirname(__DIR__) . '/autoload.php';
//exit((new Bootstrap())(PHP_SAPI === 'cli-server' ? 'hal-app' : 'prod-hal-app', $GLOBALS, $_SERVER)); // old
exit((new Bootstrap())(((PHP_SAPI === 'cli-server' || getenv('LOCAL_MODE') === '1') ? '' : 'prod-') . (str_contains($_SERVER['HTTP_ACCEPT'], 'application/json') ? 'hal-api-app' : 'html-app'), $GLOBALS, $_SERVER));
