<?php

declare(strict_types=1);

use BEAR\Package\Compiler;

require dirname(__DIR__) . '/vendor/autoload.php';

$appDir = dirname(__DIR__);

$context = $argv[1] ?? null;
if ($context === null) {
    fwrite(STDERR, 'usage: php bin/compile.php <context>' . PHP_EOL);
    exit(1);
}

// One context per process: Compiler declares generated classes (e.g. null-object stubs) as it
// runs, and a second compile in the same process fatals redeclaring them.
$code = (new Compiler('MyVendor\MyProject', $context, $appDir))();
if ($code !== 0) {
    exit($code);
}

foreach (['preload.php', 'autoload.php'] as $written) {
    if (! rename($appDir . '/' . $written, $appDir . '/' . $context . '.' . $written)) {
        exit(1);
    }
}

exit(0);
