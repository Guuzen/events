<?php

namespace App\Tariff\ViewModel;

use Money\Money;

/**
 * @psalm-immutable
 */
final class TariffSegment
{
    /**
     * @var Money
     */
    private $price;

    /**
     * @var TariffTerm
     */
    private $term;
}
