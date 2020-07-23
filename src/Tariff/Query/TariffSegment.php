<?php

namespace App\Tariff\Query;

use Money\Money;

/**
 * @psalm-immutable
 */
final class TariffSegment
{
    /**
     * @var Money
     */
    public $price;

    /**
     * @var TariffTerm
     */
    public $term;
}
