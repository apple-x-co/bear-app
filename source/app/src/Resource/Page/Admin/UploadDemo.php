<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use MyVendor\MyProject\Form\UploadFilesInterface;
use MyVendor\MyProject\Input\Admin\UploadDemo as Input;
use MyVendor\MyProject\Resource\Page\AdminPage;
use Ray\Di\Di\Assisted;
use Ray\Di\Di\Named;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

use function is_array;

class UploadDemo extends AdminPage
{
    public function __construct(
        #[Named('admin_upload_demo_form')] protected readonly FormInterface $form,
    ) {
        $this->body['form'] = $this->form;
    }

    public function onGet(): static
    {
        return $this;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @FormValidation()
     */
    public function onPost(
        Input $uploadDemo,
        #[Assisted] ?UploadFilesInterface $uploadFiles = null,
    ): static {
        $uploadedFileMap = $uploadFiles?->getUploadedFileMap();
        if (is_array($uploadedFileMap) && ! empty($uploadedFileMap)) {
            $this->body['uploadedUserFile'] = $uploadedFileMap['uploadDemo']['userFile'] ?? null;
        }

        return $this;
    }

    public function onPostValidationFailed(): static
    {
        return $this;
    }
}
