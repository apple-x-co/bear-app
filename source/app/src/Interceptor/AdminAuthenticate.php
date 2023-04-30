<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Interceptor;

use AppCore\Domain\LoggerInterface;
use Aura\Auth\Exception\MultipleMatches as AuraMultipleMatches;
use Aura\Auth\Exception\PasswordIncorrect as AuraPasswordIncorrect;
use Aura\Auth\Exception\PasswordMissing as AuraPasswordMissing;
use Aura\Auth\Exception\UsernameMissing as AuraUsernameMissing;
use Aura\Auth\Exception\UsernameNotFound as AuraUsernameNotFound;
use BEAR\Resource\NullRenderer;
use BEAR\Resource\ResourceObject;
use DateTimeImmutable;
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Annotation\AdminLogin;
use MyVendor\MyProject\Annotation\AdminLogout;
use MyVendor\MyProject\Annotation\AdminVerifyPassword;
use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use MyVendor\MyProject\Auth\AdminPasswordLocking;
use MyVendor\MyProject\Auth\MaxAttemptsExceeded;
use MyVendor\MyProject\Auth\MultipleMatches;
use MyVendor\MyProject\Auth\PasswordIncorrect;
use MyVendor\MyProject\Auth\PasswordMissing;
use MyVendor\MyProject\Auth\UsernameMissing;
use MyVendor\MyProject\Auth\UsernameNotFound;
use MyVendor\MyProject\Input\Admin\LoginUser;
use MyVendor\MyProject\Input\Admin\UserPassword;
use MyVendor\MyProject\Session\SessionInterface;
use MyVendor\MyProject\Throttle\LoginThrottleInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use Ray\Di\Di\Named;
use Throwable;

use function assert;
use function call_user_func;
use function is_string;

/**
 * Admin認証
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AdminAuthenticate implements MethodInterceptor
{
    public function __construct(
        private readonly AdminAuthenticatorInterface $authenticator,
        #[Named('admin')] private readonly LoggerInterface $logger,
        #[Named('admin')] private readonly LoginThrottleInterface $loginThrottle,
        private readonly SessionInterface $session,
    ) {
    }

    public function invoke(MethodInvocation $invocation): mixed
    {
        $method = $invocation->getMethod();

        $login = $method->getAnnotation(AdminLogin::class);
        if ($login instanceof AdminLogin) {
            return $this->login($invocation, $login->onFailure);
        }

        $logout = $method->getAnnotation(AdminLogout::class);
        if ($logout instanceof AdminLogout) {
            return $this->logout($invocation);
        }

        $verifyPassword = $method->getAnnotation(AdminVerifyPassword::class);
        if ($verifyPassword instanceof AdminVerifyPassword) {
            return $this->verifyPassword($invocation, $verifyPassword->onFailure);
        }

        return $invocation->proceed();
    }

    /**
     * @SuppressWarnings(PHPMD.ElseExpression)
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private function login(MethodInvocation $invocation, string $onFailure): mixed
    {
        $args = $invocation->getNamedArguments();
        $loginUser = $args['loginUser'] ?? null;
        assert($loginUser instanceof LoginUser);

        if ($loginUser->isValid()) {
            if ($this->loginThrottle->isExceeded($loginUser->username)) {
                return call_user_func(
                    [$invocation->getThis(), $onFailure],
                    new MaxAttemptsExceeded(),
                );
            }

            try {
                if ($loginUser->remember === 'yes') {
                    $this->authenticator->rememberLogin($loginUser->username, $loginUser->password);
                } else {
                    $this->authenticator->login($loginUser->username, $loginUser->password);
                }
            } catch (Throwable $throwable) {
                $class = match ($throwable::class) {
                    AuraUsernameMissing::class => UsernameMissing::class,
                    AuraPasswordMissing::class => PasswordMissing::class,
                    AuraUsernameNotFound::class => UsernameNotFound::class,
                    AuraMultipleMatches::class => MultipleMatches::class,
                    AuraPasswordIncorrect::class => PasswordIncorrect::class,
                    default => null,
                };

                if ($class === null) {
                    throw $throwable;
                }

                $remoteIp = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
                $this->loginThrottle->countUp($loginUser->username, $remoteIp);

                return call_user_func(
                    [$invocation->getThis(), $onFailure],
                    new $class(
                        $throwable->getMessage(),
                        $throwable->getCode(),
                        $throwable->getPrevious()
                    )
                );
            }

            $this->logger->log('[logged] ' . $loginUser->username);

            $this->loginThrottle->clear($loginUser->username);

            $continue = $this->session->get('admin:continue', '');
            $expire = (new DateTimeImmutable())->modify('+5 min')->getTimestamp();
            $this->session->set('admin:continue', '');
            $this->session->set('admin:protect:continue', '');
            $this->session->set('admin:protect:locking', AdminPasswordLocking::Unlocked->name);
            $this->session->set('admin:protect:expire', (string) $expire);

            $ro = $invocation->proceed();
            assert($ro instanceof ResourceObject);

            $ro->setRenderer(new NullRenderer());
            $ro->code = StatusCode::FOUND;
            $ro->headers = [
                'Location' => $continue === '' ?
                    $this->authenticator->getAuthRedirect() . '?logged' :
                    $continue,
            ];

            return $ro;
        }

        $ro = $invocation->getThis();
        assert($ro instanceof ResourceObject);

        $ro->setRenderer(new NullRenderer());
        $ro->code = StatusCode::FOUND;
        $ro->headers = ['Location' => $this->authenticator->getUnauthRedirect() . '?parameter_error'];

        return $ro;
    }

    private function logout(MethodInvocation $invocation): mixed
    {
        $this->session->set('admin:continue', '');
        $this->session->set('admin:protect:continue', '');
        $this->session->set('admin:protect:locking', AdminPasswordLocking::Locked->name);
        $this->session->set('admin:protect:expire', '0');

        $this->authenticator->logout();

        $ro = $invocation->proceed();
        assert($ro instanceof ResourceObject);

        $ro->setRenderer(new NullRenderer());
        $ro->code = StatusCode::FOUND;
        $ro->headers = ['Location' => $this->authenticator->getUnauthRedirect() . '?logged_out'];

        return $ro;
    }

    private function verifyPassword(MethodInvocation $invocation, string $onFailure): mixed
    {
        $args = $invocation->getNamedArguments();
        $userPassword = $args['userPassword'] ?? null;
        assert($userPassword instanceof UserPassword);

        $userName = $this->authenticator->getUserName();

        if (is_string($userName)) {
            try {
                $this->authenticator->verifyPassword(
                    $userName,
                    $userPassword->password,
                );
            } catch (AuraPasswordIncorrect $passwordIncorrect) {
                return call_user_func(
                    [$invocation->getThis(), $onFailure],
                    new PasswordIncorrect(
                        $passwordIncorrect->getMessage(),
                        $passwordIncorrect->getCode(),
                        $passwordIncorrect->getPrevious()
                    )
                );
            }

            $continue = $this->session->get('admin:protect:continue', '');
            $expire = (new DateTimeImmutable())->modify('+5 min')->getTimestamp();
            $this->session->set('admin:protect:continue', '');
            $this->session->set('admin:protect:locking', AdminPasswordLocking::Unlocked->name);
            $this->session->set('admin:protect:expire', (string) $expire);

            $ro = $invocation->proceed();
            assert($ro instanceof ResourceObject);

            $ro->setRenderer(new NullRenderer());
            $ro->code = StatusCode::FOUND;
            $ro->headers = [
                'Location' => $continue === '' ?
                    $this->authenticator->getAuthRedirect() :
                    $continue,
            ];

            return $ro;
        }

        return call_user_func(
            [$invocation->getThis(), $onFailure],
            new PasswordIncorrect()
        );
    }
}
