<?php

declare(strict_types=1);

namespace AppCore\Domain\Mail;

enum Priority: string
{
    case High = 'high';
    case Normal = 'normal';
}
