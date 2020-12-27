<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer\Tests\NormalizeTest;

use App\Infrastructure\InlineNormalizer\InlineDenormalizable;

/**
 * @InlineDenormalizable()
 */
final class WithTwoProperties
{
    private $foo;

    private $bar;

    public function __construct(string $foo, string $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }
}
