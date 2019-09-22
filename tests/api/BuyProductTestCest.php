<?php

namespace App\Tests;

use App\Tests\Contract\AppRequest\Event\CreateEvent;
use App\Tests\Contract\AppRequest\Order\MarkOrderPaid;
use App\Tests\Contract\AppRequest\Order\PlaceOrder;
use App\Tests\Contract\AppRequest\Tariff\CreateTariff;
use App\Tests\Contract\AppRequest\Ticket\CreateTicket;
use App\Tests\Contract\AppResponse\EmailWithTicket;
use App\Tests\Contract\AppResponse\EventById;
use App\Tests\Contract\AppResponse\EventInList;
use App\Tests\Contract\AppResponse\OrderById;
use App\Tests\Contract\AppResponse\OrderInList;
use App\Tests\Contract\AppResponse\TariffById\TariffById;
use App\Tests\Contract\AppResponse\TariffInList\TariffInList;
use App\Tests\Contract\AppResponse\TicketById;
use App\Tests\Contract\AppResponse\TicketInList;
use App\Tests\Step\Api\Manager;
use App\Tests\Step\Api\Visitor;

// TODO HATEOAS ?
// TODO gherkin ?
// TODO make contract folder group request/response by feature
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

    public function silverTicketByCardWithoutPromocode(Manager $manager, Visitor $visitor): void
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

        $orderId = $visitor->placeOrder(PlaceOrder::withPaymentMethodCardWith($tariffId));
        $visitor->grabPaymentLink();
        $manager->seeOrderInList($eventId, OrderInList::anySilverNotPaidNotDeliveredWith($orderId, $eventId, $tariffId, $ticketId));
        $manager->seeOrderById($orderId, OrderById::anySilverNotPaidNotDeliveredWith($orderId, $eventId, $tariffId, $ticketId));
        $manager->seeTicketInList($eventId, TicketInList::anySilverReservedNotDeliveredWith($ticketId, $eventId));
        $manager->seeTicketById($ticketId, TicketById::anySilverReservedNotDeliveredWith($ticketId, $eventId));
    }
}
