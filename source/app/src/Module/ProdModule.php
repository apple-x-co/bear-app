<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use BEAR\QiqModule\QiqErrorModule;
use BEAR\QiqModule\QiqProdModule;
use Ray\Di\AbstractModule;

class ProdModule extends AbstractModule
{
    protected function configure()
    {
        $this->install(new QiqErrorModule());
        $appDir = dirname(__DIR__, 2);
        $this->install(new QiqProdModule($appDir . '/var/tmp'));
    }
}
