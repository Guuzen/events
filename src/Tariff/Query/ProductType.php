<?php

declare(strict_types=1);

namespace App\Tariff\Query;

use App\Infrastructure\InlineNormalizer\InlineDenormalizable;

/**
 * @InlineDenormalizable()
 *
 * @psalm-immutable
 */
final class ProductType
{
    /**
     * @var string
     */
    private $type;
}
