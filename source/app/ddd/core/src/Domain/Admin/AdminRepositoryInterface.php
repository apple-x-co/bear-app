<?php

declare(strict_types=1);

namespace AppCore\Domain\Admin;

interface AdminRepositoryInterface
{
    public function findById(int $id): Admin;

    public function findByEmailAddress(string $emailAddress): ?Admin;

    public function store(Admin $admin): void;
}
