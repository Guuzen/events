<?php

namespace spec\App\Discount;

use App\Discount\Discount;
use PhpSpec\ObjectBehavior;

class DiscountSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Discount::class);
    }
}
