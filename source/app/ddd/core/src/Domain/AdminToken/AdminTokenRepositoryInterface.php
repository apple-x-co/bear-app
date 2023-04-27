<?php

declare(strict_types=1);

namespace AppCore\Domain\AdminToken;

interface AdminTokenRepositoryInterface
{
    public function findByToken(string $token): ?AdminToken;

    public function store(AdminToken $adminToken): void;
}
