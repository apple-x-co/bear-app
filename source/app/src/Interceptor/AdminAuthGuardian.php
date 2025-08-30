<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Interceptor;

use AppCore\Domain\Auth\AdminAuthenticatorInterface;
use AppCore\Domain\Session\SessionInterface;
use BEAR\Resource\NullRenderer;
use BEAR\Resource\ResourceObject;
use BEAR\Sunday\Extension\Router\RouterInterface;
use Koriym\HttpConstants\StatusCode;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

use function assert;

readonly class AdminAuthGuardian implements MethodInterceptor
{
    public function __construct(
        private AdminAuthenticatorInterface $authenticator,
        private RouterInterface $router,
        private SessionInterface $session,
    ) {
    }

    public function invoke(MethodInvocation $invocation): mixed
    {
        if ($this->authenticator->isValid()) {
            return $invocation->proceed();
        }

        $ro = $invocation->getThis();
        assert($ro instanceof ResourceObject);

        $uri = $ro->uri;
        if ($uri->method === 'get') {
            $path = $this->router->generate($uri->path, $uri->query);
            $this->session->set('admin:continue', $path === false ? '' : $path);
        }

        $ro->setRenderer(new NullRenderer());
        $ro->code = StatusCode::FOUND;
        $ro->headers = [
            'Location' => $this->authenticator->isExpired() ?
                $this->authenticator->getUnauthRedirect() . '?expired' :
                $this->authenticator->getUnauthRedirect() . '?unauthenticated',
        ];
        $ro->view = '';
        $ro->body = [];

        return $ro;
    }
}
