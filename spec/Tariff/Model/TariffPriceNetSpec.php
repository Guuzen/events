<?php

namespace spec\App\Tariff\Model;

use App\Promocode\Model\Discount\Discount;
use App\Promocode\Model\Promocode;
use App\Tariff\Model\Exception\TariffSegmentsCantIntersects;
use App\Tariff\Model\TariffSegment;
use App\Tariff\Model\TariffTerm;
use DateTimeImmutable;
use Money\Currency;
use Money\Money;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TariffPriceNetSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $segmentOneStart = new DateTimeImmutable('now');
        $segmentOneEnd   = new DateTimeImmutable('tomorrow');
        $segmentOne      = new TariffSegment(
            new Money(0, new Currency('RUB')),
            new TariffTerm($segmentOneStart, $segmentOneEnd)
        );
        $this->beConstructedWith([$segmentOne]);

        $this
            ->shouldNotThrow()
            ->duringInstantiation()
        ;
    }

    function it_cant_contain_intersecting_segments()
    {
        $segmentOneStart = new DateTimeImmutable('now');
        $segmentOneEnd   = new DateTimeImmutable('tomorrow');
        $segmentOne      = new TariffSegment(
            new Money(0, new Currency('RUB')),
            new TariffTerm($segmentOneStart, $segmentOneEnd)
        );
        $segmentTwoStart = new DateTimeImmutable('now');
        $segmentTwoEnd   = new DateTimeImmutable('tomorrow');
        $segmentTwo      = new TariffSegment(
            new Money(0, new Currency('RUB')),
            new TariffTerm($segmentTwoStart, $segmentTwoEnd)
        );
        $this->beConstructedWith([$segmentOne, $segmentTwo]);

        $this
            ->shouldThrow(TariffSegmentsCantIntersects::class)
            ->duringInstantiation()
        ;
    }

    function it_should_calculate_sum_with_promocode_if_price_defined(Discount $discount)
    {
        $today           = new DateTimeImmutable('today');
        $segmentOneStart = new DateTimeImmutable('yesterday');
        $segmentOneEnd   = new DateTimeImmutable('tomorrow');
        $segmentOne      = new TariffSegment(
            new Money(0, new Currency('RUB')),
            new TariffTerm($segmentOneStart, $segmentOneEnd)
        );
        $this->beConstructedWith([$segmentOne]);

        $discount->beADoubleOf(Discount::class);
        $discount
            ->apply(Argument::any())
            ->willReturn(new Money(100, new Currency('RUB')))
        ;

        $this
            ->calculateSum($discount, $today)
            ->shouldBeLike(new Money(100, new Currency('RUB')))
        ;
    }

    function it_should_not_calculate_sum_with_promocode_if_price_not_defined(Discount $discount)
    {
        $yesterday       = new DateTimeImmutable('yesterday');
        $segmentOneStart = new DateTimeImmutable('now');
        $segmentOneEnd   = new DateTimeImmutable('tomorrow');
        $segmentOne      = new TariffSegment(
            new Money(0, new Currency('RUB')),
            new TariffTerm($segmentOneStart, $segmentOneEnd)
        );
        $this->beConstructedWith([$segmentOne]);

        $discount->beADoubleOf(Discount::class);
        $discount
            ->apply(Argument::any())
            ->willReturn(new Money(100, new Currency('RUB')))
        ;

        $this
            ->calculateSum($discount, $yesterday)
            ->shouldBeNull()
        ;
    }
}
