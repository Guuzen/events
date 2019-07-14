<?php

namespace spec\App\Promocode\Model\AllowedTariffs;

use App\Tariff\Model\TicketTariffId;
use PhpSpec\ObjectBehavior;

class EventAllowedTariffsSpec extends ObjectBehavior
{
    function it_is_contains_tariff()
    {
        $tariffId = new TicketTariffId();

        $this
            ->contains($tariffId)
            ->shouldReturn(true)
        ;
    }
}
