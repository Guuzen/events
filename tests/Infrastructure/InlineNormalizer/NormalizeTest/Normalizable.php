<?php

declare(strict_types=1);

namespace Tests\Infrastructure\InlineNormalizer\NormalizeTest;

final class Normalizable
{
    private $foo;

    public function __construct($foo)
    {
        $this->foo = $foo;
    }
}
