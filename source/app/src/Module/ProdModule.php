<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use BEAR\Package\AbstractAppModule;
use BEAR\Package\Context\ProdModule as PackageProdModule;
use BEAR\QueryRepository\CacheVersionModule;
use BEAR\Resource\Module\OptionsMethodModule;
use MyVendor\MyProject\TemplateEngine\QiqErrorModule;
use MyVendor\MyProject\TemplateEngine\QiqProdModule;

use function time;

class ProdModule extends AbstractAppModule
{
    protected function configure(): void
    {
        $this->install(new QiqErrorModule());
        $this->install(new QiqProdModule($this->appMeta->appDir . '/var/tmp'));

        $this->install(new PackageProdModule());
        $this->override(new OptionsMethodModule());
        $this->install(new CacheVersionModule((string) time()));

        // TODO: install memcached or redis
    }
}
