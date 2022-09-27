<?php
namespace Qiq\Helper;

class Hello extends Helper
{
    public function __invoke(string $text): string
    {
        return 'Hello ' . $text . '!!';
    }
}
