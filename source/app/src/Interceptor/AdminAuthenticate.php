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
use MyVendor\MyProject\Auth\MultipleMatches;
use MyVendor\MyProject\Auth\PasswordIncorrect;
use MyVendor\MyProject\Auth\PasswordMissing;
use MyVendor\MyProject\Auth\UsernameMissing;
use MyVendor\MyProject\Auth\UsernameNotFound;
use MyVendor\MyProject\Input\Admin\LoginUser;
use MyVendor\MyProject\Input\Admin\UserPassword;
use MyVendor\MyProject\Session\SessionInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use Ray\Di\Di\Named;

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

    private function login(MethodInvocation $invocation, string $onFailure): mixed
    {
        $args = $invocation->getNamedArguments();
        $loginUser = $args['loginUser'] ?? null;
        assert($loginUser instanceof LoginUser);

        if ($loginUser->isValid()) {
            try {
                $this->authenticator->login($loginUser->username, $loginUser->password);
                $this->logger->log('[logged] ' . $loginUser->username);
            } catch (AuraUsernameMissing $usernameMissing) {
                return call_user_func(
                    [$invocation->getThis(), $onFailure],
                    new UsernameMissing(
                        $usernameMissing->getMessage(),
                        $usernameMissing->getCode(),
                        $usernameMissing->getPrevious()
                    )
                );
            } catch (AuraPasswordMissing $passwordMissing) {
                return call_user_func(
                    [$invocation->getThis(), $onFailure],
                    new PasswordMissing(
                        $passwordMissing->getMessage(),
                        $passwordMissing->getCode(),
                        $passwordMissing->getPrevious()
                    )
                );
            } catch (AuraUsernameNotFound $usernameNotFound) {
                return call_user_func(
                    [$invocation->getThis(), $onFailure],
                    new UsernameNotFound(
                        $usernameNotFound->getMessage(),
                        $usernameNotFound->getCode(),
                        $usernameNotFound->getPrevious()
                    )
                );
            } catch (AuraMultipleMatches $multipleMatches) {
                return call_user_func(
                    [$invocation->getThis(), $onFailure],
                    new MultipleMatches(
                        $multipleMatches->getMessage(),
                        $multipleMatches->getCode(),
                        $multipleMatches->getPrevious()
                    )
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
