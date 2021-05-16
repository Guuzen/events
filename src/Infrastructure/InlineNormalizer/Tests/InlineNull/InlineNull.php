<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer\Tests\InlineNull;

use App\Infrastructure\InlineNormalizer\Inline;

/**
 * @Inline
 */
final class InlineNull
{
    /**
     * @var string|null
     */
    private $foo;

    /**
     * @param string|null $foo
     */
    public function __construct(?string $foo)
    {
        $this->foo = $foo;
    }
}