<?php

declare(strict_types=1);

namespace App\Tariff\ViewModel;

use App\Infrastructure\InlineNormalizer\InlineDenormalizable;
use App\Infrastructure\InlineNormalizer\InlineNormalizable;

/**
 * @InlineNormalizable()
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
