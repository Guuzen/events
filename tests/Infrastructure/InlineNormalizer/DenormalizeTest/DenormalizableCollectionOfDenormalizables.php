<?php

declare(strict_types=1);

namespace Tests\Infrastructure\InlineNormalizer\DenormalizeTest;

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

    public function __construct(array $denormalizables)
    {
        $this->denormalizables = $denormalizables;
    }
}
