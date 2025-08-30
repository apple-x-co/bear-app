<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use Ray\MediaQuery\Pages;
use Ray\MediaQuery\PagesInterface;

readonly class Pagination implements PagesInterface
{
    /** @param Pages<mixed> $pages */
    public function __construct(private Pages $pages)
    {
    }

    public function count(): int
    {
        return $this->pages->count();
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->pages->offsetExists($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        $page = $this->pages->offsetGet($offset);
        if ($page === null) {
            return null;
        }

        return new Page($page);
    }

    /** @param int $offset */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->pages->offsetSet($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->pages->offsetUnset($offset);
    }
}
