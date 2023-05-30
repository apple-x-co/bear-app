<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use BEAR\Resource\Module\SchemeCollectionProvider;
use BEAR\Resource\SchemeCollectionInterface;
use BEAR\Sunday\Annotation\DefaultSchemeHost;
use MyVendor\MyProject\Provider\CommandSchemeCollectionProvider;
use Ray\Di\AbstractModule;

class CommandModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->bind(SchemeCollectionProvider::class);
        $this->bind(SchemeCollectionInterface::class)->toProvider(CommandSchemeCollectionProvider::class);
        $this->bind()->annotatedWith(DefaultSchemeHost::class)->toInstance('command://self');
    }
}
