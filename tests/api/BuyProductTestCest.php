<?php

namespace App\Tests;

use App\Tests\AppRequest\Event\CreateEvent;
use App\Tests\AppRequest\Order\MarkOrderPaid;
use App\Tests\AppRequest\Order\PlaceOrder;
use App\Tests\AppRequest\Tariff\CreateTariff;
use App\Tests\AppRequest\Ticket\CreateTicket;
use App\Tests\AppResponse\EmailWithTicket;
use App\Tests\AppResponse\EventById;
use App\Tests\AppResponse\EventInList;
use App\Tests\AppResponse\OrderById;
use App\Tests\AppResponse\OrderInList;
use App\Tests\AppResponse\TariffById;
use App\Tests\AppResponse\TariffInList;
use App\Tests\AppResponse\TicketById;
use App\Tests\AppResponse\TicketInList;
use App\Tests\Step\Api\Manager;
use App\Tests\Step\Api\Visitor;

// TODO HATEOAS ?
class BuyProductTestCest
{
    public function silverTicketByWireWithoutPromocode(Manager $manager, Visitor $visitor): void
    {
        $eventId = $manager->createsEvent(CreateEvent::any());
        $manager->seeEventInList(EventInList::anyWith($eventId));
        $manager->seeEventById($eventId, EventById::anyWith($eventId));

        $tariffId = $manager->createsTariff(CreateTariff::anySilverActiveNowWith($eventId));
        $manager->seeTariffInList($eventId, TariffInList::anySilverActiveNowWith($tariffId));
        $manager->seeTariffById($tariffId, TariffById::anySilverActiveNowWith($tariffId));

        $ticketId = $manager->createsTicket(CreateTicket::anyWith($eventId, $tariffId));
        $manager->seeTicketInList($eventId, TicketInList::anySilverNotReservedNotDeliveredWith($ticketId, $eventId));
        $manager->seeTicketById($ticketId, TicketById::anySilverNotReservedNotDeliveredWith($ticketId, $eventId));

        $orderId = $visitor->placeOrder(PlaceOrder::withPaymentMethodWireWith($tariffId));
        $manager->seeOrderInList($eventId, OrderInList::anySilverNotPaidNotDeliveredWith($orderId, $eventId, $tariffId, $ticketId));
        $manager->seeOrderById($orderId, OrderById::anySilverNotPaidNotDeliveredWith($orderId, $eventId, $tariffId, $ticketId));
        $manager->seeTicketInList($eventId, TicketInList::anySilverReservedNotDeliveredWith($ticketId, $eventId));
        $manager->seeTicketById($ticketId, TicketById::anySilverReservedNotDeliveredWith($ticketId, $eventId));

        $manager->markOrderPaid(MarkOrderPaid::with($orderId));
        $manager->seeEmailWithTicketSent(EmailWithTicket::any());
        $manager->seeOrderInList($eventId, OrderInList::anySilverPaidDeliveredWith($orderId, $eventId, $tariffId, $ticketId));
        $manager->seeOrderById($orderId, OrderById::anySilverPaidDeliveredWith($orderId, $eventId, $tariffId, $ticketId));
        $manager->seeTicketById($ticketId, TicketById::anySilverReservedDeliveredWith($ticketId, $eventId));
        $manager->seeTicketInList($eventId, TicketInList::anySilverReservedDeliveredWith($ticketId, $eventId));

//        $promocodeId = $manager->createsPromocode($eventId, $tariffId);
//        $manager->seePromocodeCreated($eventId, $tariffId, $promocodeId);
    }
}
