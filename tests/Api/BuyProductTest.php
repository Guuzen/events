<?php

declare(strict_types=1);

namespace Tests\Api;

use InterNations\Component\HttpMock\PHPUnit\HttpMockTrait;
use PHPUnit\Framework\TestCase;
use Tests\Api\Actor\Fondy;
use Tests\Api\Actor\Manager;
use Tests\Api\Actor\Visitor;

final class BuyProductTest extends TestCase
{
    use HttpMockTrait;

    private $manager;

    private $visitor;

    private $fondy;

    protected function setUp(): void
    {
        $this->manager = new Manager();
        $this->visitor = new Visitor();
        $this->fondy   = new Fondy();
        $opt           = [
            \PDO::ATTR_ERRMODE          => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $pdo           = new \PDO('pgsql:host=db;port=5432;dbname=guuzen-events', 'user', 'password', $opt);

        $pdo->exec('
            DO $$
            BEGIN
                execute
                (
                    select \'truncate table \' || string_agg(quote_ident(table_name), \', \')
                    from information_schema.tables
                    where table_schema = \'public\'
                );
            END
            $$;
        ');
    }

    protected function tearDown(): void
    {
        $this->visitor->tearDown();
    }

    /**
     * @test
     */
    public function byWireWithoutPromocode(): void
    {
        $eventId = $this->manager->createsEvent([
            'name'   => '2019 foo event',
            'domain' => '2019foo.event.com',
        ]);
        $this->manager->seeEventInList([
            'data' => [
                [
                    'id'     => $eventId,
                    'name'   => '2019 foo event',
                    'domain' => '2019foo.event.com',
                ],
            ],
        ]);
        $this->manager->seeEventById($eventId, [
            'data' => [
                'id'     => $eventId,
                'name'   => '2019 foo event',
                'domain' => '2019foo.event.com',
            ],
        ]);

        $tariffId = $this->manager->createsTariff([
            'eventId'     => $eventId,
            'tariffType'  => 'silver_pass',
            'segments'    => [
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
            'productType' => ['type' => 'ticket'],
        ]);
        $this->manager->seeTariffInList($eventId, [
            'data' => [
                [
                    'id'          => $tariffId,
                    'tariffType'  => 'silver_pass',
                    'segments'    => [
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
                    'productType' => 'ticket',
                ],
            ]
        ]);
        $this->manager->seeTariffById($tariffId, [
            'data' => [
                'id'          => $tariffId,
                'tariffType'  => 'silver_pass',
                'segments'    => [
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
                'productType' => 'ticket',
            ],
        ]);

        $ticketId = $this->manager->createsTicket([
            'eventId'  => $eventId,
            'tariffId' => $tariffId,
            'number'   => '10002000',
        ]);
        $this->manager->seeTicketInList($eventId, [
            'data' => [
                [
                    'id'          => $ticketId,
                    'eventId'     => $eventId,
                    'type'        => 'silver_pass',
                    'number'      => '10002000',
                    'reserved'    => false,
                    'createdAt'   => '@string@.isDateTime()',
                    'deliveredAt' => null,
                ],
            ]
        ]);
        $this->manager->seeTicketById($ticketId, [
            'data' => [
                'id'          => $ticketId,
                'eventId'     => $eventId,
                'type'        => 'silver_pass',
                'number'      => '10002000',
                'reserved'    => false,
                'createdAt'   => '@string@.isDateTime()',
                'deliveredAt' => null,
            ]
        ]);


        $orderId = $this->visitor->placeOrder([
            'tariffId'  => $tariffId,
            'firstName' => 'john',
            'lastName'  => 'Doe',
            'email'     => 'john@email.com',
            'phone'     => '+123456789',
        ]);
        $this->visitor->awaitsForEmailWithTicket();
        $this->manager->seeOrderInList($eventId, [
            'data' => [
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
                    'productId'   => '@uuid@',
                    'userId'      => '@uuid@',
                    'makedAt'     => '@string@.isDateTime()',
                    'deliveredAt' => null,
                ],
            ]
        ]);
        $this->manager->seeOrderById($orderId, [
            'data' => [
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
                'productId'   => '@uuid@',
                'userId'      => '@uuid@',
                'makedAt'     => '@string@.isDateTime()',
                'deliveredAt' => null,
            ]]);
        $this->manager->seeTicketInList($eventId, [
            'data' => [
                [
                    'id'          => $ticketId,
                    'eventId'     => $eventId,
                    'type'        => 'silver_pass',
                    'number'      => '10002000',
                    'reserved'    => true,
                    'createdAt'   => '@string@.isDateTime()',
                    'deliveredAt' => null,
                ],
            ]
        ]);
        $this->manager->seeTicketById($ticketId, [
            'data' => [
                'id'          => $ticketId,
                'eventId'     => $eventId,
                'type'        => 'silver_pass',
                'number'      => '10002000',
                'reserved'    => true,
                'createdAt'   => '@string@.isDateTime()',
                'deliveredAt' => null,
            ]
        ]);

        $this->manager->markOrderPaid([
            'orderId' => $orderId,
        ]);
        $this->visitor->receivesEmailWithTicket([
            'subject' => 'Thanks for buy ticket',
            'from'    => ['no-reply@event.com' => null],
            'to'      => ['john@email.com' => null],
        ]);
        $this->manager->seeOrderInList($eventId, [
            'data' => [
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
                    'productId'   => '@uuid@',
                    'userId'      => '@uuid@',
                    'makedAt'     => '@string@.isDateTime()',
                    'deliveredAt' => '@string@.isDateTime()',
                ],
            ]
        ]);
        $this->manager->seeOrderById($orderId, [
            'data' => [
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
                'productId'   => '@uuid@',
                'userId'      => '@uuid@',
                'makedAt'     => '@string@.isDateTime()',
                'deliveredAt' => '@string@.isDateTime()',
            ]
        ]);
        $this->manager->seeTicketById($ticketId, [
            'data' => [
                'id'          => $ticketId,
                'eventId'     => $eventId,
                'type'        => 'silver_pass',
                'number'      => '10002000',
                'reserved'    => true,
                'createdAt'   => '@string@.isDateTime()',
                'deliveredAt' => '@string@.isDateTime()',
            ]
        ]);
        $this->manager->seeTicketInList($eventId, [
            'data' => [
                [
                    'id'          => $ticketId,
                    'eventId'     => $eventId,
                    'type'        => 'silver_pass',
                    'number'      => '10002000',
                    'reserved'    => true,
                    'createdAt'   => '@string@.isDateTime()',
                    'deliveredAt' => '@string@.isDateTime()',
                ]
            ]
        ]);
    }

    /**
     * @test
     */
    public function byCardWithoutPromocode(): void
    {
        $eventId = $this->manager->createsEvent([
            'name'   => '2019 foo event',
            'domain' => '2019foo.event.com',
        ]);
        $this->manager->seeEventInList([
            'data' => [
                [
                    'id'     => $eventId,
                    'name'   => '2019 foo event',
                    'domain' => '2019foo.event.com',
                ],
            ],
        ]);
        $this->manager->seeEventById($eventId, [
            'data' => [
                'id'     => $eventId,
                'name'   => '2019 foo event',
                'domain' => '2019foo.event.com',
            ],
        ]);

        $tariffId = $this->manager->createsTariff([
            'eventId'     => $eventId,
            'tariffType'  => 'silver_pass',
            'segments'    => [
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
            'productType' => ['type' => 'ticket'],
        ]);
        $this->manager->seeTariffInList($eventId, [
            'data' => [
                [
                    'id'          => $tariffId,
                    'tariffType'  => 'silver_pass',
                    'segments'    => [
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
                    'productType' => 'ticket',
                ],
            ]
        ]);
        $this->manager->seeTariffById($tariffId, [
            'data' => [
                'id'          => $tariffId,
                'tariffType'  => 'silver_pass',
                'segments'    => [
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
                'productType' => 'ticket',
            ]
        ]);

        $ticketId = $this->manager->createsTicket([
            'eventId'  => $eventId,
            'tariffId' => $tariffId,
            'number'   => '10002000',
        ]);
        $this->manager->seeTicketInList($eventId, [
            'data' => [
                [
                    'id'          => $ticketId,
                    'eventId'     => $eventId,
                    'type'        => 'silver_pass',
                    'number'      => '10002000',
                    'reserved'    => false,
                    'createdAt'   => '@string@.isDateTime()',
                    'deliveredAt' => null,
                ],
            ]
        ]);
        $this->manager->seeTicketById($ticketId, [
            'data' => [
                'id'          => $ticketId,
                'eventId'     => $eventId,
                'type'        => 'silver_pass',
                'number'      => '10002000',
                'reserved'    => false,
                'createdAt'   => '@string@.isDateTime()',
                'deliveredAt' => null,
            ]
        ]);


        $orderId = $this->visitor->placeOrder([
            'tariffId'  => $tariffId,
            'firstName' => 'john',
            'lastName'  => 'Doe',
            'email'     => 'john@email.com',
            'phone'     => '+123456789',
        ]);
        $this->visitor->awaitsForEmailWithTicket();
        $this->manager->seeOrderInList($eventId, [
            'data' => [
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
                    'productId'   => '@uuid@',
                    'userId'      => '@uuid@',
                    'makedAt'     => '@string@.isDateTime()',
                    'deliveredAt' => null,
                ],
            ]
        ]);
        $this->manager->seeOrderById($orderId, [
            'data' => [
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
                'productId'   => '@uuid@',
                'userId'      => '@uuid@',
                'makedAt'     => '@string@.isDateTime()',
                'deliveredAt' => null,
            ]
        ]);
        $this->manager->seeTicketInList($eventId, [
            'data' => [
                [
                    'id'          => $ticketId,
                    'eventId'     => $eventId,
                    'type'        => 'silver_pass',
                    'number'      => '10002000',
                    'reserved'    => true,
                    'createdAt'   => '@string@.isDateTime()',
                    'deliveredAt' => null,
                ],
            ]
        ]);
        $this->manager->seeTicketById($ticketId, [
            'data' => [
                'id'          => $ticketId,
                'eventId'     => $eventId,
                'type'        => 'silver_pass',
                'number'      => '10002000',
                'reserved'    => true,
                'createdAt'   => '@string@.isDateTime()',
                'deliveredAt' => null,
            ]
        ]);

        $this->visitor->payOrderByCard([
            'orderId' => $orderId,
        ]);
        $this->visitor->awaitsForEmailWithTicket();
        $this->fondy->orderPaid([
            'orderId' => $orderId,
        ]);
        $this->visitor->receivesEmailWithTicket([
            'subject' => 'Thanks for buy ticket',
            'from'    => ['no-reply@event.com' => null],
            'to'      => ['john@email.com' => null],
        ]);

        $this->manager->seeOrderInList($eventId, [
            'data' => [
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
                    'productId'   => '@uuid@',
                    'userId'      => '@uuid@',
                    'makedAt'     => '@string@.isDateTime()',
                    'deliveredAt' => '@string@.isDateTime()',
                ],
            ]
        ]);
        $this->manager->seeOrderById($orderId, [
            'data' => [
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
                'productId'   => '@uuid@',
                'userId'      => '@uuid@',
                'makedAt'     => '@string@.isDateTime()',
                'deliveredAt' => '@string@.isDateTime()',
            ]
        ]);
        $this->manager->seeTicketById($ticketId, [
            'data' => [
                'id'          => $ticketId,
                'eventId'     => $eventId,
                'type'        => 'silver_pass',
                'number'      => '10002000',
                'reserved'    => true,
                'createdAt'   => '@string@.isDateTime()',
                'deliveredAt' => '@string@.isDateTime()',
            ]
        ]);
        $this->manager->seeTicketInList($eventId, [
            'data' => [
                [
                    'id'          => $ticketId,
                    'eventId'     => $eventId,
                    'type'        => 'silver_pass',
                    'number'      => '10002000',
                    'reserved'    => true,
                    'createdAt'   => '@string@.isDateTime()',
                    'deliveredAt' => '@string@.isDateTime()',
                ]
            ]
        ]);
    }

}
