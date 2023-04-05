<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use BEAR\Package\AbstractAppModule;
use BEAR\QiqModule\QiqModule;
use MyVendor\MyProject\Form\AdminLoginForm;
use MyVendor\MyProject\Form\UserLoginForm;
use Ray\AuraSessionModule\AuraSessionModule;
use Ray\WebFormModule\AuraInputModule;
use Ray\WebFormModule\FormInterface;

class HtmlModule extends AbstractAppModule
{
    protected function configure(): void
    {
        $this->install(new AuraSessionModule());
        $this->install(new QiqModule($this->appMeta->appDir . '/var/qiq/template'));
        $this->install(new AuraInputModule());
        $this->install(new SessionAuthModule());
        $this->install(new CaptchaModule());

        $this->admin();
        $this->user();
    }

    private function admin(): void
    {
        $this->bind(FormInterface::class)->annotatedWith('admin_login_form')->to(AdminLoginForm::class);
    }

    private function user(): void
    {
        $this->bind(FormInterface::class)->annotatedWith('user_login_form')->to(UserLoginForm::class);
    }
}
