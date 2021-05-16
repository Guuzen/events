<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer\Tests\InlineArray;

use App\Infrastructure\InlineNormalizer\Inline;

/**
 * @Inline()
 */
final class InlineArray
{
    /**
     * @var array
     */
    private $array;

    public function __construct(array $array)
    {
        $this->array = $array;
    }
}
