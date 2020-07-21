<?php

declare(strict_types=1);

namespace Tests\Infrastructure\InlineNormalizer\DenormalizeTest;

use App\Infrastructure\InlineNormalizer\InlineDenormalizable;

/**
 * @InlineDenormalizable()
 */
final class Denormalizable
{
    private $foo;

    public function __construct($foo)
    {
        $this->foo = $foo;
    }
}
