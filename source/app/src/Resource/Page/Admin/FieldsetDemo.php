<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use MyVendor\MyProject\Form\ExtendedForm;
use MyVendor\MyProject\Input\Admin\MultipleDemo as Input;
use MyVendor\MyProject\Resource\Page\AdminPage;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

use function assert;

class FieldsetDemo extends AdminPage
{
    public function __construct(
        #[Named('admin_fieldset_demo_form')] protected readonly FormInterface $form,
    ) {
        $this->body['form'] = $this->form;
    }

    public function onGet(): static
    {
        assert($this->form instanceof ExtendedForm);
        $this->form->fill([
            'addresses' => [[]],
        ]);

        return $this;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @FormValidation()
     */
    public function onPost(Input $multipleDemo): static
    {
        return $this;
    }

    public function onPostValidationFailed(): static
    {
        return $this;
    }
}
