<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use AppCore\Domain\FlashMessenger\FlashMessengerInterface;
use AppCore\Presentation\Shared\AdminContextInterface;
use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use MyVendor\MyProject\Auth\UserAuthenticatorInterface;
use MyVendor\MyProject\Session\SessionInterface;
use MyVendor\MyProject\Throttle\ThrottleInterface;
use Qiq\Helpers;
use Ray\Di\AbstractModule;
use Ray\WebFormModule\FormInterface;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class DefaultModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->bind(FlashMessengerInterface::class)->toNull();

        $this->bind(SessionInterface::class)->toNull();

        $this->bind()->annotatedWith('qiq_extension')->toInstance('.php');
        $this->bind()->annotatedWith('qiq_paths')->toInstance([]);
        $this->bind(Helpers::class)->to(Helpers::class);
        $this->bind()->annotatedWith('qiq_error_view_name')->toInstance('Error');

        $this->bind()->annotatedWith('google_recaptcha_site_key')->toInstance('');
        $this->bind()->annotatedWith('google_recaptcha_secret_key')->toInstance('');

        $this->bind(ThrottleInterface::class)->toNull();

        $this->admin();
        $this->user();
    }

    private function admin(): void
    {
        $this->bind(AdminAuthenticatorInterface::class)->toNull();

        $this->bind(AdminContextInterface::class)->toNull();

        $this->bind(FormInterface::class)->annotatedWith('admin_code_verify_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('admin_delete_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('admin_email_create_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('admin_forgot_password_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('admin_join_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('admin_login_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('admin_password_confirm_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('admin_password_reset_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('admin_password_update_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('admin_sign_up_form')->toNull();

        $this->bind(FormInterface::class)->annotatedWith('admin_upload_demo_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('admin_fieldset_demo_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('admin_multiple_demo_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('admin_contact_demo_form')->toNull();
    }

    private function user(): void
    {
        $this->bind(UserAuthenticatorInterface::class)->toNull();

        $this->bind(FormInterface::class)->annotatedWith('user_login_form')->toNull();
    }
}
