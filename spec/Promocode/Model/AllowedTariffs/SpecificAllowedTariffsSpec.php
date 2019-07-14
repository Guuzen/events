<?php

namespace spec\App\Promocode\Model\AllowedTariffs;

use App\Promocode\Model\AllowedTariffs\SpecificAllowedTariffs;
use App\Tariff\Model\TicketTariffId;
use PhpSpec\ObjectBehavior;

class SpecificAllowedTariffsSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $tariffId = new TicketTariffId();
        $allowedTariffs = [$tariffId];
        $this->beConstructedWith($allowedTariffs);
        $this->shouldHaveType(SpecificAllowedTariffs::class);
    }

    function it_is_contains_tariff()
    {
        $tariffId = new TicketTariffId();
        $allowedTariffs = [$tariffId];
        $this->beConstructedWith($allowedTariffs);

        $this
            ->contains($tariffId)
            ->shouldReturn(true)
        ;
    }
}
