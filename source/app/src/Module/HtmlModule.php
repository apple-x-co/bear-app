<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Module;

use AppCore\Presentation\Shared\AdminContext;
use AppCore\Presentation\Shared\AdminContextInterface;
use BEAR\Package\AbstractAppModule;
use MyVendor\MyProject\Form\AdminCodeVerifyForm;
use MyVendor\MyProject\Form\AdminContactDemoForm;
use MyVendor\MyProject\Form\AdminDeleteForm;
use MyVendor\MyProject\Form\AdminEmailCreateForm;
use MyVendor\MyProject\Form\AdminFieldsetDemoForm;
use MyVendor\MyProject\Form\AdminForgotPasswordForm;
use MyVendor\MyProject\Form\AdminJoinForm;
use MyVendor\MyProject\Form\AdminLoginForm;
use MyVendor\MyProject\Form\AdminMultipleDemoForm;
use MyVendor\MyProject\Form\AdminPasswordConfirmForm;
use MyVendor\MyProject\Form\AdminPasswordResetForm;
use MyVendor\MyProject\Form\AdminPasswordUpdateForm;
use MyVendor\MyProject\Form\AdminSignUpForm;
use MyVendor\MyProject\Form\AdminUpload2DemoForm;
use MyVendor\MyProject\Form\AdminUploadDemoForm;
use MyVendor\MyProject\Form\UploadFilesInterface;
use MyVendor\MyProject\Form\UserLoginForm;
use MyVendor\MyProject\Provider\UploadedFilesProvider;
use MyVendor\MyProject\TemplateEngine\QiqCustomHelpers;
use MyVendor\MyProject\TemplateEngine\QiqErrorModule;
use MyVendor\MyProject\TemplateEngine\QiqModule;
use Qiq\Helpers;
use Ray\AuraSessionModule\AuraSessionModule;
use Ray\WebFormModule\AuraInputModule;
use Ray\WebFormModule\FormInterface;

/** @SuppressWarnings(PHPMD.CouplingBetweenObjects) */
class HtmlModule extends AbstractAppModule
{
    protected function configure(): void
    {
        $this->install(new AuraSessionModule());
        $this->bind(Helpers::class)->to(QiqCustomHelpers::class);
        $this->install(new QiqModule([$this->appMeta->appDir . '/var/qiq/template']));
        $this->install(new QiqErrorModule('DebugTrace'));
        $this->install(new AuraInputModule());
        $this->install(new SessionAuthModule());
        $this->install(new CaptchaModule());
        $this->install(new ThrottlingModule());

        $this->bind(UploadFilesInterface::class)->toProvider(UploadedFilesProvider::class);

        $this->admin();
        $this->user();
    }

    private function admin(): void
    {
        $this->bind(AdminContextInterface::class)->to(AdminContext::class);

        $this->bind(FormInterface::class)->annotatedWith('admin_code_verify_form')->to(AdminCodeVerifyForm::class);
        $this->bind(FormInterface::class)->annotatedWith('admin_delete_form')->to(AdminDeleteForm::class);
        $this->bind(FormInterface::class)->annotatedWith('admin_email_create_form')->to(AdminEmailCreateForm::class);
        $this->bind(FormInterface::class)->annotatedWith('admin_forgot_password_form')->to(AdminForgotPasswordForm::class);
        $this->bind(FormInterface::class)->annotatedWith('admin_join_form')->to(AdminJoinForm::class);
        $this->bind(FormInterface::class)->annotatedWith('admin_login_form')->to(AdminLoginForm::class);
        $this->bind(FormInterface::class)->annotatedWith('admin_password_confirm_form')->to(AdminPasswordConfirmForm::class);
        $this->bind(FormInterface::class)->annotatedWith('admin_password_reset_form')->to(AdminPasswordResetForm::class);
        $this->bind(FormInterface::class)->annotatedWith('admin_password_update_form')->to(AdminPasswordUpdateForm::class);
        $this->bind(FormInterface::class)->annotatedWith('admin_sign_up_form')->to(AdminSignUpForm::class);

        $this->bind(FormInterface::class)->annotatedWith('admin_upload_demo_form')->to(AdminUploadDemoForm::class);
        $this->bind(FormInterface::class)->annotatedWith('admin_upload2_demo_form')->to(AdminUpload2DemoForm::class);
        $this->bind(FormInterface::class)->annotatedWith('admin_fieldset_demo_form')->to(AdminFieldsetDemoForm::class);
        $this->bind(FormInterface::class)->annotatedWith('admin_multiple_demo_form')->to(AdminMultipleDemoForm::class);
        $this->bind(FormInterface::class)->annotatedWith('admin_contact_demo_form')->to(AdminContactDemoForm::class);
    }

    private function user(): void
    {
        $this->bind(FormInterface::class)->annotatedWith('user_login_form')->to(UserLoginForm::class);
    }
}
