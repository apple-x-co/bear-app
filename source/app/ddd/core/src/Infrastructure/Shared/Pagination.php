<?php

declare(strict_types=1);

namespace AppCore\Infrastructure\Shared;

use Ray\MediaQuery\Pages;
use Ray\MediaQuery\PagesInterface;

readonly class Pagination implements PagesInterface
{
    public function __construct(
        private readonly Pages $pages,
    ) {
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
        return new Page($this->pages->offsetGet($offset));
    }

    /**
     * @param int $offset
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->pages->offsetSet($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->pages->offsetUnset($offset);
    }
}
