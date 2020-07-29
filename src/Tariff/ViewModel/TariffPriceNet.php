<?php

namespace App\Tariff\ViewModel;

use App\Infrastructure\InlineNormalizer\InlineNormalizable;

/**
 * @InlineNormalizable()
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
