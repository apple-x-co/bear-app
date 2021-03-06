<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use BEAR\Dotenv\Dotenv;
use BEAR\Package\AbstractAppModule;
use BEAR\Package\PackageModule;
use MyVendor\MyProject\Annotation\BenchMark;
use MyVendor\MyProject\Interceptor\BenchMarker;
use MyVendor\MyProject\MyLogger;
use MyVendor\MyProject\MyLoggerInterface;

use function dirname;

class AppModule extends AbstractAppModule
{
    protected function configure(): void
    {
        (new Dotenv())->load(dirname(__DIR__, 2));
        $this->bind(MyLoggerInterface::class)->to(MyLogger::class);
        $this->bindInterceptor(
            $this->matcher->any(),
            $this->matcher->annotatedWith(BenchMark::class),
            [BenchMarker::class]
        );
        $this->install(new PackageModule());
    }
}
