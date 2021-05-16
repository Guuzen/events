<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer\Tests\InlineScalar;

use App\Infrastructure\InlineNormalizer\Inline;

/**
 * @Inline
 */
final class InlineScalar
{
    /**
     * @var string
     */
    private $scalar;

    public function __construct(string $scalar)
    {
        $this->scalar = $scalar;
    }
}
