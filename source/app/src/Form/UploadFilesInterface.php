<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

use Psr\Http\Message\UploadedFileInterface;

interface UploadFilesInterface
{
    /**
     * @return array<UploadedFileInterface>
     */
    public function getUploadedFiles(): array;
}
