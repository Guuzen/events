<?php

declare(strict_types=1);

namespace Tests\Infrastructure\InlineNormalizer\NormalizeTest;

use App\Infrastructure\InlineNormalizer\InlineDenormalizable;

/**
 * @InlineDenormalizable()
 */
final class WithTwoProperties
{
    private $foo;

    private $bar;

    public function __construct($foo, $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }
}
