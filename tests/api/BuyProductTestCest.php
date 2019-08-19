<?php

namespace App\Tests;

use App\Tests\AppRequest\Event\CreateEventBuilder;
use App\Tests\AppRequest\MarkOrderPaid\MarkOrderPaidBuilder;
use App\Tests\AppRequest\Order\CreateOrderBuilder;
use App\Tests\AppRequest\Tariff\CreateTariffBuilder;
use App\Tests\AppRequest\Ticket\CreateTicketBuilder;
use App\Tests\AppResponse\EventById\EventByIdBuilder;
use App\Tests\AppResponse\EventInList\EventInListBuilder;
use App\Tests\AppResponse\OrderById\OrderByIdBuilder;
use App\Tests\AppResponse\OrderInOrderList\OrderInOrderListBuilder;
use App\Tests\AppResponse\TariffById\TariffByIdBuilder;
use App\Tests\AppResponse\TariffInList\TariffInListBuilder;
use App\Tests\AppResponse\TicketById\TicketByIdBuilder;
use App\Tests\AppResponse\TicketInTicketList\TicketInTicketListBuilder;
use App\Tests\Step\Api\Manager;
use App\Tests\Step\Api\Visitor;

// TODO HATEOAS ?
// TODO need generator for reguest/response data structures and their builders
// TODO override seeresponsejson serializer ?
class BuyProductTestCest
{
    public function silverTicketByWireWithoutPromocode(Manager $manager, Visitor $visitor): void
    {
        $eventId = $manager->createsEvent(CreateEventBuilder::any()->build());
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
            CreateTariffBuilder::any()
                ->withEventId($eventId)
                ->withProductType('silver_pass')
                ->build()
        );
        $manager->seeTariffInTariffList(
            $eventId,
            TariffInListBuilder::any()
                ->withId($tariffId)
                ->withProductType('silver_pass')
                ->build()
        );
        $manager->seeTariffById($tariffId,
            TariffByIdBuilder::any()
                ->withId($tariffId)
                ->withProductType('silver_pass')
                ->build()
        );

        $ticketId = $manager->createsTicket(
            CreateTicketBuilder::any()
                ->withEventId($eventId)
                ->withTariffId($tariffId)
                ->build()
        );
        $manager->seeTicketInTicketList(
            $eventId,
            TicketInTicketListBuilder::any()
                ->withId($ticketId)
                ->withEventId($eventId)
                ->withTicketType('silver_pass')
                ->build()
        );
        $manager->seeTicketById(
            $ticketId,
            TicketByIdBuilder::any()
                ->withId($ticketId)
                ->withEventId($eventId)
                ->withTicketType('silver_pass')
                ->build()
        );

        $orderId = $visitor->placeOrder(
            CreateOrderBuilder::any()
                ->withTariffId($tariffId)
                ->withPaymentMethod('wire')
                ->build()
        );
        $manager->seeOrderInOrderList(
            $eventId,
            OrderInOrderListBuilder::any()
                ->withId($orderId)
                ->withEventId($eventId)
                ->withTariffId($tariffId)
                ->withProductId($ticketId)
                ->withProduct('silver_pass')
                ->build()
        );
        $manager->seeOrderById(
            $orderId,
            OrderByIdBuilder::any()
                ->withId($orderId)
                ->withEventId($eventId)
                ->withTariffId($tariffId)
                ->withProductId($ticketId)
                ->withProduct('silver_pass')
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
