<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use MyVendor\MyProject\Auth\AdminAuthenticatorInterface;
use MyVendor\MyProject\Auth\UserAuthenticatorInterface;
use MyVendor\MyProject\Form\UploadFilesInterface;
use MyVendor\MyProject\Session\SessionInterface;
use Ray\Di\AbstractModule;
use Ray\WebFormModule\FormInterface;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class DefaultModule extends AbstractModule
{
    protected function configure(): void
    {
        $this->bind(UploadFilesInterface::class)->toNull();

        $this->bind(SessionInterface::class)->toNull();
        $this->bind()->annotatedWith('qiq_template_dir')->toInstance('');

        $this->bind()->annotatedWith('g_recaptcha_site_key')->toInstance('');
        $this->bind()->annotatedWith('g_recaptcha_secret_key')->toInstance('');

        $this->admin();
        $this->user();
    }

    private function admin(): void
    {
        $this->bind(AdminAuthenticatorInterface::class)->toNull();

        $this->bind(FormInterface::class)->annotatedWith('admin_login_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('admin_password_confirm_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('admin_password_update_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('admin_upload_demo_form')->toNull();
        $this->bind(FormInterface::class)->annotatedWith('admin_upload2_demo_form')->toNull();
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
