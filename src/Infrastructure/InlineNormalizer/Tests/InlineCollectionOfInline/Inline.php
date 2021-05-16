<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer\Tests\InlineCollectionOfInline;

/**
 * @\App\Infrastructure\InlineNormalizer\Inline
 */
final class Inline
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
}
