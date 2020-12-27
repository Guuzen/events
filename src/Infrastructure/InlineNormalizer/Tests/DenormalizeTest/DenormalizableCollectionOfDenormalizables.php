<?php

declare(strict_types=1);

namespace App\Infrastructure\InlineNormalizer\Tests\DenormalizeTest;

use App\Infrastructure\InlineNormalizer\InlineDenormalizable;

/**
 * @InlineDenormalizable()
 */
final class DenormalizableCollectionOfDenormalizables
{
    /**
     * @var Denormalizable[]
     */
    private $denormalizables;

    /**
     * @param Denormalizable[] $denormalizables
     */
    public function __construct(array $denormalizables)
    {
        $this->denormalizables = $denormalizables;
    }
}
