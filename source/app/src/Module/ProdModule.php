<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use BEAR\Package\AbstractAppModule;
use BEAR\Package\Context\ProdModule as PackageProdModule;
use BEAR\QueryRepository\CacheVersionModule;
use BEAR\Resource\Module\OptionsMethodModule;
use MyVendor\MyProject\TemplateEngine\QiqProdModule;
use Override;

use function time;

final class ProdModule extends AbstractAppModule
{
    #[Override]
    protected function configure(): void
    {
        $this->install(new QiqProdModule($this->appMeta->appDir . '/var/tmp'));

        $this->install(new PackageProdModule());
        $this->override(new OptionsMethodModule());
        $this->install(new CacheVersionModule((string) time()));

        // 本番では Remember me の Cookie を HTTPS でだけ送る
        $this->bind()->annotatedWith('remember_cookie_secure')->toInstance(true);

        // TODO: install memcached or redis
    }
}
