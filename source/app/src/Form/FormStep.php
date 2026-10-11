<?php

declare(strict_types=1);

namespace MyVendor\MyProject\Form;

enum FormStep: string
{
    case Input = 'Input';
    case Confirm = 'Confirm';
    case Complete = 'Complete';

    public function isConfirm(): bool
    {
        return $this === self::Confirm;
    }

    public function isComplete(): bool
    {
        return $this === self::Complete;
    }
}
