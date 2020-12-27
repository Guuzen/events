<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer\Tests\NormalizeTest;

final class Normalizable
{
    /**
     * @var mixed
     */
    private $foo;

    /**
     * @param mixed $foo
     */
    public function __construct($foo)
    {
        $this->foo = $foo;
    }
}
