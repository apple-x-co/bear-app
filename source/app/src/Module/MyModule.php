<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use AppCore\Domain\Admin\AdminRepositoryInterface;
use AppCore\Domain\AdminToken\AdminTokenRepositoryInterface;
use AppCore\Domain\Encrypter;
use AppCore\Domain\EncrypterInterface;
use AppCore\Domain\LoggerInterface;
use AppCore\Domain\SecureRandom;
use AppCore\Domain\SecureRandomInterface;
use AppCore\Domain\Test\TestRepositoryInterface;
use AppCore\Domain\Throttle\ThrottleRepositoryInterface;
use AppCore\Infrastructure\Persistence\AdminRepository;
use AppCore\Infrastructure\Persistence\AdminTokenRepository;
use AppCore\Infrastructure\Persistence\TestRepository;
use AppCore\Infrastructure\Persistence\ThrottleRepository;
use AppCore\Infrastructure\Shared\AdminLogger;
use AppCore\Infrastructure\Shared\UserLogger;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

use function getenv;
use function random_bytes;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class MyModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->core();
        $this->logger();
        $this->repository();

        // TODO: AWS SKD の設定などはここで行う。ステージング環境や本番環境で必要な設定は ProdModule で行う。
    }

    private function core(): void
    {
        $this->bind()->annotatedWith('encrypt_pass')->toInstance((string) getenv('ENCRYPT_PASS'));
        $this->bind(EncrypterInterface::class)->to(Encrypter::class)->in(Scope::SINGLETON);

        $this->bind()->annotatedWith('hash_salt')->toInstance(random_bytes(32));
        $this->bind(SecureRandomInterface::class)->to(SecureRandom::class)->in(Scope::SINGLETON);
    }

    private function logger(): void
    {
        $this->bind(LoggerInterface::class)->annotatedWith('admin')->to(AdminLogger::class);
        $this->bind(LoggerInterface::class)->annotatedWith('user')->to(UserLogger::class);
    }

    private function repository(): void
    {
        $this->bind(AdminRepositoryInterface::class)->to(AdminRepository::class)->in(Scope::SINGLETON);
        $this->bind(AdminTokenRepositoryInterface::class)->to(AdminTokenRepository::class)->in(Scope::SINGLETON);
        $this->bind(TestRepositoryInterface::class)->to(TestRepository::class)->in(Scope::SINGLETON);
        $this->bind(ThrottleRepositoryInterface::class)->to(ThrottleRepository::class)->in(Scope::SINGLETON);
    }
}
