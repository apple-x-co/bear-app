<?php

declare(strict_types=1);

namespace MyVendor\MyProject\TemplateEngine;

use BEAR\Package\Provide\Error\ErrorPageFactoryInterface;
use BEAR\Resource\ResourceObject;
use BEAR\Sunday\Extension\Router\RouterMatch;
use Qiq\Template;
use Ray\Di\Di\Named;
use Throwable;

final readonly class QiqErrorPageFactory implements ErrorPageFactoryInterface
{
    public function __construct(
        #[Named('qiq_error_view_name')]
        private string $errorViewName,
        private Template $template,
    ) {
    }

    public function newInstance(Throwable $e, RouterMatch $request): ResourceObject
    {
        return new QiqErrorPage(
            $e,
            $this->errorViewName,
            $request,
            $this->template,
        );
    }
}
