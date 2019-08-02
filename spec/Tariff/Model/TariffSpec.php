<?php

namespace spec\App\Tariff\Model;

use App\Order\Model\OrderId;
use App\Promocode\Model\NullPromocode;
use App\Tariff\Model\Exception\DisableNotExistsOrderIsNotPossible;
use App\Tariff\Model\Exception\TariffAlreadyCreatedInOrder;
use App\Tariff\Model\Exception\TariffTermNotMatchOrderMakeAt;
use App\Tariff\Model\Tariff;
use App\Tariff\Model\TicketTariffId;
use App\Tariff\Model\TariffTerm;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TariffSpec extends ObjectBehavior
{
    function it_is_initializable()
    {

    }
}
