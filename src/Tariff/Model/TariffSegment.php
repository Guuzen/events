<?php

namespace App\Tariff\Model;

use App\Promocode\Model\Discount\Discount;
use DateTimeImmutable;
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

    public function __construct(Money $price, TariffTerm $term)
    {
        $this->price = $price;
        $this->term  = $term;
    }

    public function includes(DateTimeImmutable $dateTime): bool
    {
        return $this->term->includes($dateTime);
    }

    // TODO overlaps see fowler date range
    public function intersects(TariffSegment $price): bool
    {
        return $this->term->intersects($price->term);
    }

    public function calculateSum(Discount $discount): Money
    {
        return $discount->apply($this->price);
    }
}
