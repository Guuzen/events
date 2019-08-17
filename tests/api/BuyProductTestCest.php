<?php

namespace App\Tests;

use App\Tests\Step\Api\Manager;
use App\Tests\Step\Api\Visitor;

class BuyProductTestCest
{
    public function paymentForSilverTicketByWireWithoutPromocode(Manager $manager, Visitor $visitor): void
    {
        $eventId = $manager->createsFoo2019Event();
        $manager->seeEventInEventList($eventId);

        // TODO additionally check show page, not only list
        $tariffId = $manager->createsTariff($eventId, 'silver_pass');
        $manager->seeTariffInTariffList($eventId, $tariffId, 'silver_pass');

        $ticketId = $manager->createsTicket($eventId, $tariffId);
        $manager->seeTicketInTicketList('silver_pass');

        $visitor->placeOrder($tariffId);
        $manager->seeOrderInOrderList($eventId, $tariffId, $ticketId);

//        $promocodeId = $manager->createsPromocode($eventId, $tariffId);
//        $manager->seePromocodeCreated($eventId, $tariffId, $promocodeId);
    }
}
