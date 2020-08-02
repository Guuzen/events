<?php

namespace App\Tariff\ViewModel;

use App\Infrastructure\InlineNormalizer\InlineDenormalizable;
use App\Infrastructure\InlineNormalizer\InlineNormalizable;

/**
 * @InlineNormalizable()
 * @InlineDenormalizable()
 *
 * @psalm-immutable
 */
final class TariffPriceNet
{
    /**
     * @var TariffSegment[]
     */
    private $segments;
}
