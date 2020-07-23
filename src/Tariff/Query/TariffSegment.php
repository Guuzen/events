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
    private $price;

    /**
     * @var TariffTerm
     */
    private $term;
}
