<?php

namespace App\Tariff\ViewModel;

use App\Infrastructure\InlineNormalizer\InlineDenormalizable;

/**
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
