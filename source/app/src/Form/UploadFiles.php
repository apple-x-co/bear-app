<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Psr\Http\Message\UploadedFileInterface;

class UploadFiles implements UploadFilesInterface
{
    /**
     * @param array<UploadedFileInterface> $uploadedFiles
     */
    public function __construct(
        private readonly array $uploadedFiles,
    ) {
    }

    /**
     * @return array<UploadedFileInterface>
     */
    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }
}
