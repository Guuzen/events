<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer\Tests\InlineCollectionOfInline;

/**
 * @\App\Infrastructure\InlineNormalizer\Inline
 */
final class InlineCollectionOfInline
{
    /**
     * @var Inline[]
     */
    private $inlines;

    /**
     * @param Inline[] $inlines
     */
    public function __construct(array $inlines)
    {
        $this->inlines = $inlines;
    }
}
