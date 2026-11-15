<?php

declare(strict_types=1);

namespace AppCore\Domain;

final class CodeName
{
    public function __invoke(): string
    {
        return 'BearApp';
    }
}
