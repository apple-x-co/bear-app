<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use AppCore\Domain\Admin\AdminRepositoryInterface;

class GetAdminUseCase
{
    public function __construct(
        private readonly AdminRepositoryInterface $adminRepository,
    ) {
    }

    public function execute(GetAdminInputData $inputData): GetAdminOutputData
    {
        $admin = $this->adminRepository->findById($inputData->adminId);

        return new GetAdminOutputData($admin);
    }
}
