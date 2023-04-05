<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use AppCore\Domain\LoggerInterface;
use AppCore\Domain\Test\TestRepositoryInterface;
use AppCore\Infrastructure\Persistence\TestRepository;
use AppCore\Infrastructure\Shared\AdminLogger;
use AppCore\Infrastructure\Shared\UserLogger;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

class MyModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->logger();
        $this->repository();

        // TODO: AWS SKD の設定などはここで行う。ステージング環境や本番環境で必要な設定は ProdModule で行う。
    }

    private function logger(): void
    {
        $this->bind(LoggerInterface::class)->annotatedWith('admin')->to(AdminLogger::class);
        $this->bind(LoggerInterface::class)->annotatedWith('user')->to(UserLogger::class);
    }

    private function repository(): void
    {
        $this->bind(TestRepositoryInterface::class)->to(TestRepository::class)->in(Scope::SINGLETON);
    }
}
