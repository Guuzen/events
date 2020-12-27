<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer\Tests\DenormalizeTest;

use App\Infrastructure\InlineNormalizer\InlineDenormalizable;

/**
 * @InlineDenormalizable()
 */
final class Denormalizable
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
