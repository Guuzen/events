<?php

declare(strict_types=1);

namespace Tests\Infrastructure\InlineNormalizer\DenormalizeTest;

final class WithManyProperties
{
    private $foo;
    private $bar;

    public function __construct($foo, $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }
}
