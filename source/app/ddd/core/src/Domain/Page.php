<?php

declare(strict_types=1);

namespace AppCore\Domain;

use Ray\AuraSqlModule\Pagerfanta\Page as PagerfantaPage;
use Stringable;

readonly class Page implements Stringable
{
    public function __construct(
        private readonly PagerfantaPage $page
    ) {
    }

    public function getData(): mixed
    {
        return $this->page->data;
    }

    public function getCurrent(): int
    {
        return $this->page->current;
    }

    public function getTotal(): int
    {
        return $this->page->total;
    }

    public function hasNext(): bool
    {
        return $this->page->hasNext;
    }

    public function hasPrevious(): bool
    {
        return $this->page->hasPrevious;
    }

    public function __toString(): string
    {
        return (string) $this->page;
    }
}
