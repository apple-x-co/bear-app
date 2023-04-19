<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Resource\Page\Admin;

use Koriym\HttpConstants\StatusCode;
use MyVendor\MyProject\Form\AdminUpload2DemoForm;
use MyVendor\MyProject\Form\FormMode;
use MyVendor\MyProject\Form\UploadFilesInterface;
use MyVendor\MyProject\Input\Admin\Upload2Demo as Input;
use MyVendor\MyProject\Resource\Page\AdminPage;
use Ray\Di\Di\Assisted;
use Ray\Di\Di\Named;
use Ray\IdentityValueModule\UuidInterface;
use Ray\WebFormModule\Annotation\FormValidation;
use Ray\WebFormModule\FormInterface;

use function assert;
use function is_array;
use function move_uploaded_file;

class Upload2Demo extends AdminPage
{
    public function __construct(
        #[Named('admin_upload2_demo_form')] protected readonly FormInterface $form,
        protected readonly UuidInterface $uuid,
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
    public function onPost(
        Input $upload2Demo,
        #[Assisted] ?UploadFilesInterface $uploadFiles = null,
    ): static {
        assert($this->form instanceof AdminUpload2DemoForm);

        if ($upload2Demo->mode === FormMode::Complete->name) {
            $this->code = StatusCode::SEE_OTHER;
            $this->headers = ['Location' => '/admin/upload2-demo-complete'];

            return $this;
        }

        if ($upload2Demo->mode === FormMode::Confirm->name) {
            $fileMetaMap = $this->form->fileset->file;
            if (is_array($fileMetaMap) && $fileMetaMap['tmp_name'] !== '') {
                $tmpName = (string) $this->uuid;
                move_uploaded_file($fileMetaMap['tmp_name'], '/tmp/' . $tmpName);
                $this->form->fileset->fill([
                    'clientFileName' => $fileMetaMap['name'],
                    'clientMediaType' => $fileMetaMap['type'],
                    'size' => $fileMetaMap['size'],
                    'tmpName' => $tmpName,
                ]);
            }
        }

        return $this;
    }

    public function onPostValidationFailed(): static
    {
        assert($this->form instanceof AdminUpload2DemoForm);
        $this->form->mode = 'INPUT';

        return $this;
    }
}
