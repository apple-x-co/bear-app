<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\User;

use BEAR\RepositoryModule\Annotation\NoHttpCache;
use Koriym\FileUpload\ErrorFileUpload;
use Koriym\FileUpload\FileUpload;
use MyVendor\MyProject\InputQuery\Customer\UploadDemoInput;
use MyVendor\MyProject\Resource\Page\BaseUserPage;
use Ray\Di\Di\Named;
use Ray\InputQuery\Attribute\Input;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

#[NoHttpCache]
class UploadDemo extends BaseUserPage
{
    public function __construct(
        #[Named('user_upload_demo_form')]
        protected readonly FormInterface $form,
    ) {
        $this->body['form'] = $this->form;
    }

    public function onGet(): static
    {
        return $this;
    }

    /** @SuppressWarnings("PHPMD.UnusedFormalParameter") */
    #[FormValidation]
    public function onPost(
        #[Input]
        UploadDemoInput $input,
    ): static {
        if ($input->file instanceof FileUpload) {
            $this->body['fileUpload'] = $input->file;
        }

        if ($input->file instanceof ErrorFileUpload) {
            $this->body['errorFileUpload'] = $input->file;
        }

        return $this;
    }

    public function onPostValidationFailed(): static
    {
        return $this;
    }
}
