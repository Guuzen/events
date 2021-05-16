<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer\Tests\ItIsNotPossibleToInlineManyValues;

use App\Infrastructure\InlineNormalizer\Inline;

/**
 * @Inline
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
