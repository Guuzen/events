<?php

namespace App\Tests;

use App\Tests\Step\Api\Manager;
use App\Tests\Step\Api\Visitor;

// TODO HATEOAS ?
class BuyProductTestCest
{
    public function silverTicketByWireWithoutPromocode(Manager $manager, Visitor $visitor): void
    {
        $eventId = $manager->createsFoo2019Event();
        $manager->seeEventInEventList($eventId);
        $manager->seeEventById($eventId);

        $tariffId = $manager->createsTariff($eventId, 'silver_pass');
        $manager->seeTariffInTariffList($eventId, $tariffId, 'silver_pass');
        $manager->seeTariffById($tariffId, 'silver_pass');

        $ticketId = $manager->createsTicket($eventId, $tariffId);
        $manager->seeTicketInTicketList($eventId, $ticketId, 'silver_pass');
        $manager->seeTicketById($eventId, $ticketId, 'silver_pass');

        $orderId = $visitor->placeOrder($tariffId);
        $manager->seeOrderInOrderList($orderId, $eventId, $tariffId, $ticketId);
        $manager->seeOrderById($orderId, $eventId, $tariffId, $ticketId);

//        $promocodeId = $manager->createsPromocode($eventId, $tariffId);
//        $manager->seePromocodeCreated($eventId, $tariffId, $promocodeId);
    }
}
