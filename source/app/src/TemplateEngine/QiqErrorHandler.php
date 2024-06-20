<?php

declare(strict_types=1);

namespace MyVendor\MyProject\TemplateEngine;

use BEAR\Package\Provide\Error\ErrorLogger;
use BEAR\Package\Provide\Error\ErrorPageFactoryInterface;
use BEAR\Package\Provide\Error\NullPage;
use BEAR\Resource\ResourceObject;
use BEAR\Sunday\Extension\Error\ErrorInterface;
use BEAR\Sunday\Extension\Router\RouterMatch as Request;
use BEAR\Sunday\Extension\Transfer\TransferInterface;
use Exception;
use Ray\Di\Di\Named;

final class QiqErrorHandler implements ErrorInterface
{
    private ResourceObject|null $errorPage = null;

    public function __construct(
        private readonly TransferInterface $responder,
        private readonly ErrorLogger $logger,
        #[Named('qiq')]
        private readonly ErrorPageFactoryInterface $factory,
    ) {
    }

    /** {@inheritDoc}*/
    public function handle(Exception $e, Request $request) // phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
    {
        ($this->logger)($e, $request);
        $this->errorPage = $this->factory->newInstance($e, $request);

        return $this;
    }

    public function transfer(): void
    {
        ($this->responder)($this->errorPage ?? new NullPage(), []);
    }
}
