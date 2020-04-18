<?php

namespace App\Tests;

use App\Tests\Contract\AppRequest\Order\MarkOrderPaid;
use App\Tests\Contract\AppResponse\EmailWithTicket;
use App\Tests\Contract\AppResponse\OrderById;
use App\Tests\Contract\AppResponse\OrderInList;
use App\Tests\Contract\AppResponse\TicketById;
use App\Tests\Contract\AppResponse\TicketInList;
use App\Tests\Step\Api\Manager;

// TODO HATEOAS ?
// TODO gherkin ?
// TODO make contract folder group request/response by feature
class BuyProductTestCest
{
    public function silverTicketByWireWithoutPromocode(Manager $manager, Visitor $visitor): void
    {
        $eventId = $manager->createsEvent([
            'name'   => '2019 foo event',
            'domain' => '2019foo.event.com',
        ]);
        $manager->seeEventInList([
            [
                'id'     => $eventId,
                'name'   => '2019 foo event',
                'domain' => '2019foo.event.com',
            ],
        ]);
        $manager->seeEventById($eventId, [
            'id'     => $eventId,
            'name'   => '2019 foo event',
            'domain' => '2019foo.event.com',
        ]);

        $tariffId = $manager->createsTariff([
            'eventId'    => $eventId,
            'tariffType' => 'silver_pass',
            'segments'   => [
                [
                    'price' => [
                        'amount'   => '200',
                        'currency' => 'RUB',
                    ],
                    'term'  => [
                        'start' => '2000-01-01 12:00:00',
                        'end'   => '3000-01-01 12:00:00',
                    ],
                ],
            ],
        ]);
        $manager->seeTariffInList($eventId, [
            [
                'id'         => $tariffId,
                'tariffType' => 'silver_pass',
                'segments'   => [
                    [
                        'price' => [
                            'amount'   => '200',
                            'currency' => 'RUB',
                        ],
                        'term'  => [
                            'start' => '2000-01-01 12:00:00',
                            'end'   => '3000-01-01 12:00:00',
                        ],
                    ],
                ],
            ],
        ]);
        $manager->seeTariffById($tariffId, [
            'id'         => $tariffId,
            'tariffType' => 'silver_pass',
            'segments'   => [
                [
                    'price' => [
                        'amount'   => '200',
                        'currency' => 'RUB',
                    ],
                    'term'  => [
                        'start' => '2000-01-01 12:00:00',
                        'end'   => '3000-01-01 12:00:00',
                    ],
                ],
            ],
        ]);

        $ticketId = $manager->createsTicket([
            'eventId'  => $eventId,
            'tariffId' => $tariffId,
            'number'   => '10002000',
        ]);
        $manager->seeTicketInList($eventId, [
            [
                'id'          => $ticketId,
                'eventId'     => $eventId,
                'type'        => 'silver_pass',
                'number'      => '10002000',
                'reserved'    => false,
            ],
        ]);
        $manager->seeTicketById($ticketId, [
            'id'          => $ticketId,
            'eventId'     => $eventId,
            'type'        => 'silver_pass',
            'number'      => '10002000',
            'reserved'    => false,
        ]);

        $orderId = $visitor->placeOrder([
            'tariffId'  => $tariffId,
            'firstName' => 'john',
            'lastName'  => 'Doe',
            'email'     => 'john@email.com',
            'phone'     => '+123456789',
        ]);
        $manager->seeOrderInList($eventId, [
            [
                'id'          => $orderId,
                'eventId'     => $eventId,
                'tariffId'    => $tariffId,
                'paid'        => false,
                'product'     => 'silver_pass',
                'phone'       => '+123456789',
                'firstName'   => 'john',
                'lastName'    => 'Doe',
                'email'       => 'john@email.com',
                'sum'         => '200',
                'currency'    => 'RUB',
                'cancelled'   => false,
            ],
        ]);
        $manager->seeOrderById($orderId, [
            'id'          => $orderId,
            'eventId'     => $eventId,
            'tariffId'    => $tariffId,
            'paid'        => false,
            'product'     => 'silver_pass',
            'phone'       => '+123456789',
            'firstName'   => 'john',
            'lastName'    => 'Doe',
            'email'       => 'john@email.com',
            'sum'         => '200',
            'currency'    => 'RUB',
            'cancelled'   => false,
        ]);
        $manager->seeTicketInList($eventId, [
            [
                'id'          => $ticketId,
                'eventId'     => $eventId,
                'type'        => 'silver_pass',
                'number'      => '10002000',
                'reserved'    => true,
            ],
        ]);
        $manager->seeTicketById($ticketId, [
            'id'          => $ticketId,
            'eventId'     => $eventId,
            'type'        => 'silver_pass',
            'number'      => '10002000',
            'reserved'    => true,
        ]);

        $manager->markOrderPaid([
            'orderId' => $orderId,
        ]);
        $manager->seeEmailWithTicketSent(EmailWithTicket::any()); // TODO
        $manager->seeOrderInList($eventId, [
            [
                'id'          => $orderId,
                'eventId'     => $eventId,
                'tariffId'    => $tariffId,
                'paid'        => true,
                'product'     => 'silver_pass',
                'phone'       => '+123456789',
                'firstName'   => 'john',
                'lastName'    => 'Doe',
                'email'       => 'john@email.com',
                'sum'         => '200',
                'currency'    => 'RUB',
                'cancelled'   => false,
            ],
        ]);
        $manager->seeOrderById($orderId,             [
            'id'          => $orderId,
            'eventId'     => $eventId,
            'tariffId'    => $tariffId,
            'paid'        => true,
            'product'     => 'silver_pass',
            'phone'       => '+123456789',
            'firstName'   => 'john',
            'lastName'    => 'Doe',
            'email'       => 'john@email.com',
            'sum'         => '200',
            'currency'    => 'RUB',
            'cancelled'   => false,
        ]);
        $manager->seeTicketById($ticketId, [
            'id'          => $ticketId,
            'eventId'     => $eventId,
            'type'        => 'silver_pass',
            'number'      => '10002000',
            'reserved'    => true,
        ]);
        $manager->seeTicketInList($eventId, [
            [
                'id'          => $ticketId,
                'eventId'     => $eventId,
                'type'        => 'silver_pass',
                'number'      => '10002000',
                'reserved'    => true,
            ]
        ]);

//        $promocodeId = $manager->createsPromocode($eventId, $tariffId);
//        $manager->seePromocodeCreated($eventId, $tariffId, $promocodeId);
    }

//    public function silverTicketByCardWithoutPromocode(Manager $manager, Visitor $visitor, Fondy $fondy): void
//    {
//        $eventId = $manager->createsEvent([
//            'name'   => '2019 foo event',
//            'domain' => '2019foo.event.com',
//        ]);
//        $manager->seeEventInList(EventInList::anyWith($eventId));
//        $manager->seeEventById($eventId, EventById::anyWith($eventId));
//
//        $tariffId = $manager->createsTariff(CreateTariff::anySilverActiveNowWith($eventId));
//        $manager->seeTariffInList($eventId, TariffInList::anySilverActiveNowWith($tariffId));
//        $manager->seeTariffById($tariffId, TariffById::anySilverActiveNowWith($tariffId));
//
//        $ticketId = $manager->createsTicket(CreateTicket::anyWith($eventId, $tariffId));
//        $manager->seeTicketInList($eventId, TicketInList::anySilverNotReservedNotDeliveredWith($ticketId, $eventId));
//        $manager->seeTicketById($ticketId, TicketById::anySilverNotReservedNotDeliveredWith($ticketId, $eventId));
//
//        $orderId = $visitor->placeOrder(PlaceOrder::anyWith($tariffId, null));
//        $manager->seeOrderInList($eventId, OrderInList::anySilverNotPaidNotDeliveredWith($orderId, $eventId, $tariffId, $ticketId));
//        $manager->seeOrderById($orderId, OrderById::anySilverNotPaidNotDeliveredWith($orderId, $eventId, $tariffId, $ticketId));
//        $manager->seeTicketInList($eventId, TicketInList::anySilverReservedNotDeliveredWith($ticketId, $eventId));
//        $manager->seeTicketById($ticketId, TicketById::anySilverReservedNotDeliveredWith($ticketId, $eventId));
//
//        $visitor->payOrderByCard(PayOrderByCard::with($orderId));
//
//        $fondy->orderPaid(MarkPaidByFondy::with($orderId));
//        $manager->seeEmailWithTicketSent(EmailWithTicket::any());
//        $manager->seeOrderInList($eventId, OrderInList::anySilverPaidDeliveredWith($orderId, $eventId, $tariffId, $ticketId));
//        $manager->seeOrderById($orderId, OrderById::anySilverPaidDeliveredWith($orderId, $eventId, $tariffId, $ticketId));
//        $manager->seeTicketInList($eventId, TicketInList::anySilverReservedDeliveredWith($ticketId, $eventId));
//        $manager->seeTicketById($ticketId, TicketById::anySilverReservedDeliveredWith($ticketId, $eventId));
//    }
//
//    public function silverTicketByWireWithPromocode(Manager $manager, Visitor $visitor): void
//    {
//        $eventId = $manager->createsEvent([
//            'name'   => '2019 foo event',
//            'domain' => '2019foo.event.com',
//        ]);
//        $manager->seeEventInList(EventInList::anyWith($eventId));
//        $manager->seeEventById($eventId, EventById::anyWith($eventId));
//
//        $tariffId = $manager->createsTariff(CreateTariff::anySilverActiveNowWith($eventId));
//        $manager->seeTariffInList($eventId, TariffInList::anySilverActiveNowWith($tariffId));
//        $manager->seeTariffById($tariffId, TariffById::anySilverActiveNowWith($tariffId));
//
//        $ticketId = $manager->createsTicket(CreateTicket::anyWith($eventId, $tariffId));
//        $manager->seeTicketInList($eventId, TicketInList::anySilverNotReservedNotDeliveredWith($ticketId, $eventId));
//        $manager->seeTicketById($ticketId, TicketById::anySilverNotReservedNotDeliveredWith($ticketId, $eventId));
//
//        $promocode   = 'FIXED-DISCOUNT-10-RUB';
//        $promocodeId = $manager->createsPromocode(CreateFixedPromocode::any10RubWith($eventId, $promocode, [$tariffId]));
//        $manager->seePromocodeCreatedInList($eventId, PromocodeInList::anyFixed10RubWith($promocodeId, $promocode));
//
////        $orderId = $visitor->placeOrder(PlaceOrder::anyWith($tariffId, $promocode));
////        $manager->seeOrderInList($eventId, OrderInList::anySilverNotPaidNotDeliveredWith($orderId, $eventId, $tariffId, $ticketId));
////        $manager->seeOrderById($orderId, OrderById::anySilverNotPaidNotDeliveredWith($orderId, $eventId, $tariffId, $ticketId));
////        $manager->seeTicketInList($eventId, TicketInList::anySilverReservedNotDeliveredWith($ticketId, $eventId));
////        $manager->seeTicketById($ticketId, TicketById::anySilverReservedNotDeliveredWith($ticketId, $eventId));
////
////        $manager->markOrderPaid(MarkOrderPaid::with($orderId));
////        $manager->seeEmailWithTicketSent(EmailWithTicket::any());
////        $manager->seeOrderInList($eventId, OrderInList::anySilverPaidDeliveredWith($orderId, $eventId, $tariffId, $ticketId));
////        $manager->seeOrderById($orderId, OrderById::anySilverPaidDeliveredWith($orderId, $eventId, $tariffId, $ticketId));
////        $manager->seeTicketById($ticketId, TicketById::anySilverReservedDeliveredWith($ticketId, $eventId));
////        $manager->seeTicketInList($eventId, TicketInList::anySilverReservedDeliveredWith($ticketId, $eventId));
//    }
}
