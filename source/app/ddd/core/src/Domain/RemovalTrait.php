<?php

declare(strict_types=1);

namespace AppCore\Domain;

trait RemovalTrait // phpcs:ignore
{
    private bool $removal = false;

    public function isRemoval(): bool
    {
        return $this->removal;
    }

    public function setRemoval(bool $removal): void
    {
        $this->removal = $removal;
    }
}
