<?php

declare(strict_types=1);

namespace MyVendor\MyProject\TemplateEngine;

use BEAR\Package\Provide\Error\ErrorPageFactoryInterface;
use BEAR\Resource\RenderInterface;
use BEAR\Sunday\Extension\Error\ErrorInterface;
use Qiq\Catalog;
use Qiq\Compiler;
use Qiq\Compiler\QiqCompiler;
use Qiq\Engine;
use Qiq\Helper\Html\HtmlHelpers;
use Qiq\Helpers;
use Qiq\Template;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class QiqModule extends AbstractModule
{
    /** @param array<string> $paths */
    public function __construct(
        private readonly array $paths,
        private AbstractModule|null $module = null,
    ) {
        parent::__construct($this->module);
    }

    protected function configure(): void
    {
        $this->bind(Template::class)->in(Scope::SINGLETON);
        $this->bind(Engine::class)->to(Template::class)->in(Scope::SINGLETON);
        $this->bind(Catalog::class)->toConstructor(
            Catalog::class,
            [
                'paths' => 'qiq_paths',
                'extension' => 'qiq_extension',
            ],
        );
        $this->bind()->annotatedWith('qiq_cache_path')->toInstance(null);
        $this->bind()->annotatedWith('qiq_extension')->toInstance('.php');
        $this->bind()->annotatedWith('qiq_paths')->toInstance($this->paths);
        $this->bind(RenderInterface::class)->to(QiqRenderer::class)->in(Scope::SINGLETON);
        $this->bind(Helpers::class)->to(HtmlHelpers::class);
        $this->bind(Compiler::class)->to(QiqCompiler::class);

        $this->bind()->annotatedWith('qiq_error_view_name')->toInstance('DebugTrace');
        $this->bind(ErrorPageFactoryInterface::class)->annotatedWith('qiq')->to(QiqErrorPageFactory::class);
        $this->bind(ErrorInterface::class)->to(QiqErrorHandler::class);
    }
}
