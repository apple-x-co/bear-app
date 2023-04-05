<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Interceptor;

use BEAR\Resource\NullRenderer;
use BEAR\Resource\ResourceObject;
use BEAR\Sunday\Extension\Router\RouterInterface;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Auth\UserAuthenticatorInterface;
use MyVendor\MyProject\Session\SessionInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

use function assert;

class UserAuthGuardian implements MethodInterceptor
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly SessionInterface $session,
        private readonly UserAuthenticatorInterface $authenticator,
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
            $this->session->set('user:continue', $path === false ? '' : $path);
        }

        $ro->setRenderer(new NullRenderer());
        $ro->code = StatusCode::FOUND;
        $ro->headers = [
            'Location' => $this->authenticator->isExpired() ?
                $this->authenticator->getUnauthRedirect() . '?expired' :
                $this->authenticator->getUnauthRedirect() . '?unauthenticated',
        ];

        return $ro;
    }
}
