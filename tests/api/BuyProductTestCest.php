<?php

namespace App\Tests;

use App\Tests\AppRequest\Event\EventBuilder;
use App\Tests\AppRequest\MarkOrderPaid\MarkOrderPaidBuilder;
use App\Tests\AppRequest\Order\OrderBuilder;
use App\Tests\AppRequest\Tariff\TariffBuilder;
use App\Tests\AppRequest\Ticket\TicketBuilder;
use App\Tests\AppResponse\EventById\EventByIdBuilder;
use App\Tests\AppResponse\EventInList\EventInListBuilder;
use App\Tests\AppResponse\OrderById\OrderByIdBuilder;
use App\Tests\AppResponse\OrderInOrderList\OrderInOrderListBuilder;
use App\Tests\AppResponse\TariffById\TariffByIdBuilder;
use App\Tests\AppResponse\TariffInList\TariffInListBuilder;
use App\Tests\AppResponse\TicketInTicketList\TicketInTicketListBuilder;
use App\Tests\Step\Api\Manager;
use App\Tests\Step\Api\Visitor;

// TODO HATEOAS ?
// TODO need generator for reguest/response data structures and their builders
// TODO override seeresponsejson serializer ?
// TODO group request/response by methods ?
class BuyProductTestCest
{
    // TODO specify data according to name of the method
    public function silverTicketByWireWithoutPromocode(Manager $manager, Visitor $visitor): void
    {
        // TODO reduce noice introduced by builders
        $eventId = $manager->createsEvent(EventBuilder::any()->build());
        $manager->seeEventInEventList(
            EventInListBuilder::any()
                ->withId($eventId)
                ->build()
        );
        $manager->seeEventById(
            $eventId,
            EventByIdBuilder::any()
                ->withId($eventId)
                ->build()
        );

        $tariffId = $manager->createsTariff(
            TariffBuilder::any()
                ->withEventId($eventId)
                ->build()
        );
        $manager->seeTariffInTariffList(
            $eventId,
            TariffInListBuilder::any()
                ->withId($tariffId)
                ->build()
        );
        $manager->seeTariffById($tariffId,
            TariffByIdBuilder::any()
                ->withId($tariffId)
                ->build()
        );

        $ticketId = $manager->createsTicket(
            TicketBuilder::any()
                ->withEventId($eventId)
                ->withTariffId($tariffId)
                ->build()
        );
        $manager->seeTicketInTicketList(
            $eventId,
            TicketInTicketListBuilder::any()
                ->withId($ticketId)
                ->withEventId($eventId)
                ->build()
        );
        $manager->seeTicketById($eventId, $ticketId, 'silver_pass');

        $orderId = $visitor->placeOrder(
            OrderBuilder::any()
                ->withTariffId($tariffId)
                ->build()
        );
        $manager->seeOrderInOrderList(
            $eventId,
            OrderInOrderListBuilder::any()
                ->withId($orderId)
                ->withEventId($eventId)
                ->withTariffId($tariffId)
                ->withProductId($ticketId)
                ->build()
        );
        $manager->seeOrderById(
            $orderId,
            OrderByIdBuilder::any()
                ->withId($orderId)
                ->withEventId($eventId)
                ->withTariffId($tariffId)
                ->withProductId($ticketId)
                ->build()
        );

        // TODO check that email was sent
        $manager->markOrderPaid(
            MarkOrderPaidBuilder::any()
                ->withOrderId($orderId)
                ->build()
        );
        $manager->seeOrderInOrderList(
            $eventId,
            OrderInOrderListBuilder::any()
                ->withId($orderId)
                ->withEventId($eventId)
                ->withTariffId($tariffId)
                ->withProductId($ticketId)
                ->withPaid(true)
                ->build()
        );
        $manager->seeOrderById(
            $orderId,
            OrderByIdBuilder::any()
                ->withId($orderId)
                ->withEventId($eventId)
                ->withTariffId($tariffId)
                ->withProductId($ticketId)
                ->withPaid(true)
                ->build()
        );

//        $promocodeId = $manager->createsPromocode($eventId, $tariffId);
//        $manager->seePromocodeCreated($eventId, $tariffId, $promocodeId);
    }
}
