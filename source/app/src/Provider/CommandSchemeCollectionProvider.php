<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Provider;

use BEAR\Resource\Annotation\AppName;
use BEAR\Resource\AppAdapter;
use BEAR\Resource\Module\SchemeCollectionProvider;
use BEAR\Resource\SchemeCollection;
use Ray\Di\InjectorInterface;
use Ray\Di\ProviderInterface;

class CommandSchemeCollectionProvider implements ProviderInterface
{
    /** @SuppressWarnings(PHPMD.LongVariable) */
    public function __construct(
        #[AppName] private readonly string $appName,
        private readonly InjectorInterface $injector,
        private readonly SchemeCollectionProvider $schemeCollectionProvider,
    ) {
    }

    public function get(): SchemeCollection
    {
        $schemeCollection = $this->schemeCollectionProvider->get();
        $commandAdapter = new AppAdapter($this->injector, $this->appName);
        $schemeCollection->scheme('command')->host('self')->toAdapter($commandAdapter);

        return $schemeCollection;
    }
}
