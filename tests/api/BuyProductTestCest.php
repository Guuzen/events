<?php

namespace App\Tests;

use App\Tests\Contract\AppResponse\EmailWithTicket;

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
                'id'       => $ticketId,
                'eventId'  => $eventId,
                'type'     => 'silver_pass',
                'number'   => '10002000',
                'reserved' => false,
            ],
        ]);
        $manager->seeTicketById($ticketId, [
            'id'       => $ticketId,
            'eventId'  => $eventId,
            'type'     => 'silver_pass',
            'number'   => '10002000',
            'reserved' => false,
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
                'id'        => $orderId,
                'eventId'   => $eventId,
                'tariffId'  => $tariffId,
                'paid'      => false,
                'product'   => 'silver_pass',
                'phone'     => '+123456789',
                'firstName' => 'john',
                'lastName'  => 'Doe',
                'email'     => 'john@email.com',
                'sum'       => '200',
                'currency'  => 'RUB',
                'cancelled' => false,
            ],
        ]);
        $manager->seeOrderById($orderId, [
            'id'        => $orderId,
            'eventId'   => $eventId,
            'tariffId'  => $tariffId,
            'paid'      => false,
            'product'   => 'silver_pass',
            'phone'     => '+123456789',
            'firstName' => 'john',
            'lastName'  => 'Doe',
            'email'     => 'john@email.com',
            'sum'       => '200',
            'currency'  => 'RUB',
            'cancelled' => false,
        ]);
        $manager->seeTicketInList($eventId, [
            [
                'id'       => $ticketId,
                'eventId'  => $eventId,
                'type'     => 'silver_pass',
                'number'   => '10002000',
                'reserved' => true,
            ],
        ]);
        $manager->seeTicketById($ticketId, [
            'id'       => $ticketId,
            'eventId'  => $eventId,
            'type'     => 'silver_pass',
            'number'   => '10002000',
            'reserved' => true,
        ]);

        $manager->markOrderPaid([
            'orderId' => $orderId,
        ]);
        $manager->seeEmailWithTicketSent(EmailWithTicket::any()); // TODO
        $manager->seeOrderInList($eventId, [
            [
                'id'        => $orderId,
                'eventId'   => $eventId,
                'tariffId'  => $tariffId,
                'paid'      => true,
                'product'   => 'silver_pass',
                'phone'     => '+123456789',
                'firstName' => 'john',
                'lastName'  => 'Doe',
                'email'     => 'john@email.com',
                'sum'       => '200',
                'currency'  => 'RUB',
                'cancelled' => false,
            ],
        ]);
        $manager->seeOrderById($orderId, [
            'id'        => $orderId,
            'eventId'   => $eventId,
            'tariffId'  => $tariffId,
            'paid'      => true,
            'product'   => 'silver_pass',
            'phone'     => '+123456789',
            'firstName' => 'john',
            'lastName'  => 'Doe',
            'email'     => 'john@email.com',
            'sum'       => '200',
            'currency'  => 'RUB',
            'cancelled' => false,
        ]);
        $manager->seeTicketById($ticketId, [
            'id'       => $ticketId,
            'eventId'  => $eventId,
            'type'     => 'silver_pass',
            'number'   => '10002000',
            'reserved' => true,
        ]);
        $manager->seeTicketInList($eventId, [
            [
                'id'       => $ticketId,
                'eventId'  => $eventId,
                'type'     => 'silver_pass',
                'number'   => '10002000',
                'reserved' => true,
            ]
        ]);
    }

    public function silverTicketByCardWithoutPromocode(Manager $manager, Visitor $visitor, Fondy $fondy): void
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
                'id'       => $ticketId,
                'eventId'  => $eventId,
                'type'     => 'silver_pass',
                'number'   => '10002000',
                'reserved' => false,
            ],
        ]);
        $manager->seeTicketById($ticketId, [
            'id'       => $ticketId,
            'eventId'  => $eventId,
            'type'     => 'silver_pass',
            'number'   => '10002000',
            'reserved' => false,
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
                'id'        => $orderId,
                'eventId'   => $eventId,
                'tariffId'  => $tariffId,
                'paid'      => false,
                'product'   => 'silver_pass',
                'phone'     => '+123456789',
                'firstName' => 'john',
                'lastName'  => 'Doe',
                'email'     => 'john@email.com',
                'sum'       => '200',
                'currency'  => 'RUB',
                'cancelled' => false,
            ],
        ]);
        $manager->seeOrderById($orderId, [
            'id'        => $orderId,
            'eventId'   => $eventId,
            'tariffId'  => $tariffId,
            'paid'      => false,
            'product'   => 'silver_pass',
            'phone'     => '+123456789',
            'firstName' => 'john',
            'lastName'  => 'Doe',
            'email'     => 'john@email.com',
            'sum'       => '200',
            'currency'  => 'RUB',
            'cancelled' => false,
        ]);
        $manager->seeTicketInList($eventId, [
            [
                'id'       => $ticketId,
                'eventId'  => $eventId,
                'type'     => 'silver_pass',
                'number'   => '10002000',
                'reserved' => true,
            ],
        ]);
        $manager->seeTicketById($ticketId, [
            'id'       => $ticketId,
            'eventId'  => $eventId,
            'type'     => 'silver_pass',
            'number'   => '10002000',
            'reserved' => true,
        ]);

        $visitor->payOrderByCard([
            'orderId' => $orderId,
        ]);

        $fondy->orderPaid([
            'orderId' => $orderId,
        ]);
        $manager->seeEmailWithTicketSent(EmailWithTicket::any());
        $manager->seeOrderInList($eventId, [
            [
                'id'        => $orderId,
                'eventId'   => $eventId,
                'tariffId'  => $tariffId,
                'paid'      => true,
                'product'   => 'silver_pass',
                'phone'     => '+123456789',
                'firstName' => 'john',
                'lastName'  => 'Doe',
                'email'     => 'john@email.com',
                'sum'       => '200',
                'currency'  => 'RUB',
                'cancelled' => false,
            ],
        ]);
        $manager->seeOrderById($orderId, [
            'id'        => $orderId,
            'eventId'   => $eventId,
            'tariffId'  => $tariffId,
            'paid'      => true,
            'product'   => 'silver_pass',
            'phone'     => '+123456789',
            'firstName' => 'john',
            'lastName'  => 'Doe',
            'email'     => 'john@email.com',
            'sum'       => '200',
            'currency'  => 'RUB',
            'cancelled' => false,
        ]);
        $manager->seeTicketById($ticketId, [
            'id'       => $ticketId,
            'eventId'  => $eventId,
            'type'     => 'silver_pass',
            'number'   => '10002000',
            'reserved' => true,
        ]);
        $manager->seeTicketInList($eventId, [
            [
                'id'       => $ticketId,
                'eventId'  => $eventId,
                'type'     => 'silver_pass',
                'number'   => '10002000',
                'reserved' => true,
            ]
        ]);
    }

    public function silverTicketByWireWithPromocode(Manager $manager, Visitor $visitor): void
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
                'id'       => $ticketId,
                'eventId'  => $eventId,
                'type'     => 'silver_pass',
                'number'   => '10002000',
                'reserved' => false,
            ],
        ]);
        $manager->seeTicketById($ticketId, [
            'id'       => $ticketId,
            'eventId'  => $eventId,
            'type'     => 'silver_pass',
            'number'   => '10002000',
            'reserved' => false,
        ]);

        $promocodeId = $manager->createsPromocode([
            'eventId'        => $eventId,
            'code'           => 'FIXED-DISCOUNT-10-RUB',
            'discount'       => [
                'amount'   => '10',
                'currency' => 'RUB',
            ],
            'useLimit'       => 1,
            'expireAt'       => '3000-12-12 00:00:00',
            'allowedTariffs' => [
                'b21ea553-3999-4c76-8a07-6f80162347b5',
            ],
            'usable'         => true,
        ]);
        $manager->seePromocodeCreatedInList($eventId, [
            [
                'id'       => $promocodeId,
                'code'     => 'FIXED-DISCOUNT-10-RUB',
                'discount' => [
                    'amount'   => '10',
                    'currency' => 'RUB',
                    'type'     => 'fixed',
                ],
                'useLimit' => 1,
                'expireAt' => '3000-12-12 00:00:00',
                'usable'   => true,
            ],
        ]);
    }
}
