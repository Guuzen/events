<?php

namespace spec\App\Discount;

use App\Discount\FixedDiscount;
use Money\Currency;
use Money\Money;
use PhpSpec\ObjectBehavior;

class FixedDiscountSpec extends ObjectBehavior
{
    public function let(): void
    {
        $tenRoubles = new Money('10', new Currency('RUB'));
        $this->beConstructedWith($tenRoubles);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(FixedDiscount::class);
    }

    public function it_can_be_applied_to_money(): void
    {
        $hundredRoubles = new Money('100', new Currency('RUB'));
        $ninetyRoubles  = new Money('90', new Currency('RUB'));
        $this
            ->applyTo($hundredRoubles)
            ->shouldBeLike($ninetyRoubles);
    }
}
