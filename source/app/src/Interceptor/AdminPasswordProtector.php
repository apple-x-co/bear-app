<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Interceptor;

use AppCore\Domain\Auth\AdminAuthenticatorInterface;
use AppCore\Domain\Auth\AdminPasswordLocking;
use AppCore\Domain\Session\SessionInterface;
use BEAR\Resource\NullRenderer;
use BEAR\Resource\ResourceObject;
use BEAR\Sunday\Extension\Router\RouterInterface;
use DateTimeImmutable;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Annotation\AdminPasswordLock;
use MyVendor\MyProject\Annotation\AdminPasswordProtect;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;

use function http_build_query;

readonly class AdminPasswordProtector implements MethodInterceptor
{
    public function __construct(
        private AdminAuthenticatorInterface $authenticator,
        private RouterInterface $router,
        private SessionInterface $session,
    ) {
    }

    /** @psalm-suppress ArgumentTypeCoercion */
    public function invoke(MethodInvocation $invocation): mixed
    {
        $protect = $invocation->getMethod()->getAnnotation(AdminPasswordProtect::class);
        if ($protect instanceof AdminPasswordProtect) {
            // @phpstan-ignore-next-line
            return $this->protect($invocation);
        }

        $lock = $invocation->getMethod()->getAnnotation(AdminPasswordLock::class);
        if ($lock instanceof AdminPasswordLock) {
            // @phpstan-ignore-next-line
            return $this->lock($invocation);
        }

        return $invocation->proceed();
    }

    /** @param MethodInvocation<ResourceObject> $invocation */
    private function protect(MethodInvocation $invocation): mixed
    {
        $now = (new DateTimeImmutable())->getTimestamp();

        $locking = $this->session->get('admin:protect:locking', AdminPasswordLocking::Locked->name);
        $expire = $this->session->get('admin:protect:expire', '0');
        if ($locking === AdminPasswordLocking::Unlocked->name && $now < (int) $expire) {
            return $invocation->proceed();
        }

        $ro = $invocation->getThis();

        $uri = $ro->uri;
        $path = $this->router->generate($uri->path, $uri->query);
        if ($path === false) {
            $path = $uri->path;
        }

        if ($uri->method === 'get') {
            $path = empty($uri->query) ? '' : '?' . http_build_query($uri->query);
        }

        $this->session->set('admin:protect:continue', $path);

        $ro->setRenderer(new NullRenderer());
        $ro->code = StatusCode::FOUND;
        $ro->headers = ['Location' => $this->authenticator->getPasswordRedirect()];
        $ro->view = '';
        $ro->body = [];

        return $ro;
    }

    /** @param MethodInvocation<ResourceObject> $invocation */
    private function lock(MethodInvocation $invocation): mixed
    {
        $this->session->set('admin:protect:locking', AdminPasswordLocking::Locked->name);
        $this->session->set('admin:protect:continue', '');
        $this->session->set('admin:protect:expire', '0');

        return $invocation->proceed();
    }
}
