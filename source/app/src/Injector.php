<?php

declare(strict_types=1);

namespace MyVendor\MyProject;

use BEAR\Package\Injector as PackageInjector;
use BEAR\Package\Types;
use Ray\Di\AbstractModule;
use Ray\Di\InjectorInterface;

use function dirname;

/**
 * @psalm-import-type Context from Types
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
final class Injector
{
    /** @codeCoverageIgnore */
    private function __construct()
    {
    }

    /** @param Context $context */
    public static function getInstance(string $context): InjectorInterface
    {
        return PackageInjector::getInstance(__NAMESPACE__, $context, dirname(__DIR__));
    }

    /** @param Context $context */
    public static function getOverrideInstance(string $context, AbstractModule $overrideModule): InjectorInterface
    {
        return PackageInjector::getOverrideInstance(__NAMESPACE__, $context, dirname(__DIR__), $overrideModule);
    }
}
