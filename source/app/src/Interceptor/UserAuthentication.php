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
use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Annotation\UserLogin;
use MyVendor\MyProject\Annotation\UserLogout;
use MyVendor\MyProject\Auth\MultipleMatches;
use MyVendor\MyProject\Auth\ParameterMissingException;
use MyVendor\MyProject\Auth\PasswordIncorrect;
use MyVendor\MyProject\Auth\PasswordMissing;
use MyVendor\MyProject\Auth\UserAuthenticatorInterface;
use MyVendor\MyProject\Auth\UsernameMissing;
use MyVendor\MyProject\Auth\UsernameNotFound;
use MyVendor\MyProject\InputQuery\User\LoginUserInput;
use MyVendor\MyProject\Session\SessionInterface;
use Ray\Aop\MethodInterceptor;
use Ray\Aop\MethodInvocation;
use Ray\Di\Di\Named;

use function assert;
use function call_user_func;
use function var_dump;

/**
 * User認証
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class UserAuthentication implements MethodInterceptor
{
    public function __construct(
        private readonly SessionInterface $session,
        #[Named('user')] private readonly LoggerInterface $logger,
        private readonly UserAuthenticatorInterface $authenticator
    ) {
    }

    public function invoke(MethodInvocation $invocation): mixed
    {
        $login = $invocation->getMethod()->getAnnotation(UserLogin::class);
        if ($login instanceof UserLogin) {
            return $this->login($invocation, $login->onFailure);
        }

        $logout = $invocation->getMethod()->getAnnotation(UserLogout::class);
        if ($logout instanceof UserLogout) {
            return $this->logout($invocation);
        }

        return $invocation->proceed();
    }

    private function login(MethodInvocation $invocation, string $onFailure): mixed
    {
        $args = $invocation->getNamedArguments();

        /** @var LoginUserInput|null $input */
        $input = null;
        foreach ($args as $value) {
            if ($value instanceof LoginUserInput) {
                $input = $value;
            }
        }

        if ($input === null) {
            return call_user_func(
                [$invocation->getThis(), $onFailure],
                new ParameterMissingException()
            );
        }

        if ($input->isValid()) {
            try {
                $this->authenticator->login($input->username, $input->password);
                $this->logger->log('[logged] ' . $input->username);
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

            $continue = $this->session->get('user:continue', '');
            $this->session->set('user:continue', '');

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
        $this->authenticator->logout();

        $ro = $invocation->proceed();
        assert($ro instanceof ResourceObject);

        $ro->setRenderer(new NullRenderer());
        $ro->code = StatusCode::FOUND;
        $ro->headers = ['Location' => $this->authenticator->getUnauthRedirect() . '?logged_out'];
        $ro->view = '';
        $ro->body = [];

        return $ro;
    }
}
