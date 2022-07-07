<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use BEAR\QiqModule\QiqModule;
use Ray\Di\AbstractModule;

use function dirname;

class HtmlModule extends AbstractModule
{
    protected function configure()
    {
        $appDir = dirname(__DIR__, 2);
        $this->install(new QiqModule($appDir . '/var/qiq/template'));
    }
}
