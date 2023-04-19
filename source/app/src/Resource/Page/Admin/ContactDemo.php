<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Form\AdminContactDemoForm;
use MyVendor\MyProject\Form\FormMode;
use MyVendor\MyProject\Input\Admin\ContactDemo as Input;
use MyVendor\MyProject\Resource\Page\AdminPage;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

use function assert;

class ContactDemo extends AdminPage
{
    public function __construct(
        #[Named('admin_contact_demo_form')] protected readonly FormInterface $form,
    ) {
        $this->body['form'] = $this->form;
        $this->body['inputMode'] = FormMode::Input;
        $this->body['confirmMode'] = FormMode::Confirm;
        $this->body['completeMode'] = FormMode::Complete;
    }

    public function onGet(): static
    {
        return $this;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @FormValidation()
     */
    public function onPost(Input $contactDemo): static
    {
        if ($contactDemo->mode === FormMode::Complete->name) {
            $this->code = StatusCode::SEE_OTHER;
            $this->headers = ['Location' => '/admin/contact-complete-demo'];

            return $this;
        }

        return $this;
    }

    public function onPostValidationFailed(): static
    {
        assert($this->form instanceof AdminContactDemoForm);
        $this->form->mode = 'INPUT';

        return $this;
    }
}
