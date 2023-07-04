<?php

declare(strict_types=1);

namespace AppCore\Application\Admin;

use AppCore\Domain\Admin\AdminRepositoryInterface;

class DeleteAdminEmailUseCase
{
    public function __construct(
        private readonly AdminRepositoryInterface $adminRepository,
    ) {
    }

    public function execute(DeleteAdminEmailInputData $inputData): void
    {
        $admin = $this->adminRepository->findById($inputData->adminId);

        foreach ($admin->emails as $email) {
            if ($email->id === $inputData->adminEmailId) {
                $this->adminRepository->store($admin->removeEmail($email));

                break;
            }
        }
    }
}
