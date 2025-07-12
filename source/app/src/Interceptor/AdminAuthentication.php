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
use MyVendor\MyProject\InputQuery\Admin\LoginUserInput;
use MyVendor\MyProject\InputQuery\Admin\UserPasswordInput;
use MyVendor\MyProject\Session\SessionInterface;
use MyVendor\MyProject\Throttle\ThrottleInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use Ray\Di\Di\Named;
use Throwable;

use function assert;
use function call_user_func;
use function is_string;
use function sha1;

/**
 * Admin認証
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AdminAuthentication implements MethodInterceptor
{
    public function __construct(
        #[Named('admin_auth_attempt_interval')] private readonly string $attemptInterval,
        private readonly AdminAuthenticatorInterface $authenticator,
        #[Named('admin')] private readonly LoggerInterface $logger,
        #[Named('admin_auth_max_attempts')] private readonly int $maxAttempts,
        private readonly SessionInterface $session,
        private readonly ThrottleInterface $throttle,
    ) {
    }

    public function invoke(MethodInvocation $invocation): mixed
    {
        $method = $invocation->getMethod();

        $login = $method->getAnnotation(AdminLogin::class);
        if ($login instanceof AdminLogin) {
            return $this->login($invocation, $login->inputName, $login->onFailure);
        }

        $logout = $method->getAnnotation(AdminLogout::class);
        if ($logout instanceof AdminLogout) {
            return $this->logout($invocation);
        }

        $verifyPassword = $method->getAnnotation(AdminVerifyPassword::class);
        if ($verifyPassword instanceof AdminVerifyPassword) {
            return $this->verifyPassword($invocation, $verifyPassword->inputName, $verifyPassword->onFailure);
        }

        return $invocation->proceed();
    }

    /**
     * @SuppressWarnings(PHPMD.ElseExpression)
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private function login(MethodInvocation $invocation, string $inputName, string $onFailure): mixed
    {
        assert($inputName !== '');

        $args = $invocation->getNamedArguments();
        $input = $args[$inputName] ?? null;
        assert($input instanceof LoginUserInput);

        if ($input->isValid()) {
            $throttleKey = sha1($input->username);

            if ($this->throttle->isExceeded($throttleKey)) {
                return call_user_func(
                    [$invocation->getThis(), $onFailure],
                    new MaxAttemptsExceeded(),
                );
            }

            try {
                if ($input->remember === 'yes') {
                    $this->authenticator->rememberLogin($input->username, $input->password);
                } else {
                    $this->authenticator->login($input->username, $input->password);
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
                $this->throttle->countUp($throttleKey, $remoteIp, $this->attemptInterval, $this->maxAttempts);

                return call_user_func(
                    [$invocation->getThis(), $onFailure],
                    new $class(
                        $throwable->getMessage(),
                        $throwable->getCode(),
                        $throwable->getPrevious()
                    )
                );
            }

            $this->logger->log('[logged] ' . $input->username);

            $this->throttle->clear($throttleKey);

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
            $ro->view = '';
            $ro->body = [];

            return $ro;
        }

        $ro = $invocation->getThis();
        assert($ro instanceof ResourceObject);

        $ro->setRenderer(new NullRenderer());
        $ro->code = StatusCode::FOUND;
        $ro->headers = ['Location' => $this->authenticator->getUnauthRedirect() . '?parameter_error'];
        $ro->view = '';
        $ro->body = [];

        return $ro;
    }

    private function logout(MethodInvocation $invocation): mixed
    {
        $this->session->set('admin:continue', '');
        $this->session->set('admin:protect:continue', '');
        $this->session->set('admin:protect:locking', AdminPasswordLocking::Locked->name);
        $this->session->set('admin:protect:expire', '0');

        $ro = $invocation->proceed();
        assert($ro instanceof ResourceObject);

        $this->authenticator->logout();

        $ro->setRenderer(new NullRenderer());
        $ro->code = StatusCode::FOUND;
        $ro->headers = ['Location' => $this->authenticator->getUnauthRedirect() . '?logged_out'];
        $ro->view = '';
        $ro->body = [];

        return $ro;
    }

    private function verifyPassword(MethodInvocation $invocation, string $inputName, string $onFailure): mixed
    {
        assert($inputName !== '');

        $args = $invocation->getNamedArguments();
        $input = $args[$inputName] ?? null;
        assert($input instanceof UserPasswordInput);

        $userName = $this->authenticator->getUserName();

        if (is_string($userName)) {
            try {
                $this->authenticator->verifyPassword(
                    $userName,
                    $input->password,
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
            $ro->view = '';
            $ro->body = [];

            return $ro;
        }

        return call_user_func(
            [$invocation->getThis(), $onFailure],
            new PasswordIncorrect()
        );
    }
}
