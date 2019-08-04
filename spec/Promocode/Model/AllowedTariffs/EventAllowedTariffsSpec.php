<?php

namespace spec\App\Promocode\Model\AllowedTariffs;

use App\Tariff\Model\TariffId;
use PhpSpec\ObjectBehavior;

class EventAllowedTariffsSpec extends ObjectBehavior
{
    function it_is_contains_tariff()
    {
        $tariffId = new TariffId();

        $this
            ->contains($tariffId)
            ->shouldReturn(true)
        ;
    }
}
