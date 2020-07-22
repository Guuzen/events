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
    public function byWire(): void
    {
        $eventId = $this->manager->createsEvent();
        $this->manager->createsEventDomain([
            'id'     => $eventId,
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
                        'start' => '2000-01-01 12:00:00Z',
                        'end'   => '3000-01-01 12:00:00Z',
                    ],
                ],
            ],
            'productType' => 'ticket',
        ]);
        $this->manager->seeTariffDetailsById($tariffId, [
            'data' => [
                'id'         => $tariffId,
                'tariffType' => 'silver_pass',
            ],
        ]);
        $this->manager->seeTariffInList($eventId, [
            'data' => [
                [
                    'id'          => $tariffId,
                    'eventId'     => $eventId,
                    'segments'    => [
                        [
                            'price' => [
                                'amount'   => '200',
                                'currency' => 'RUB',
                            ],
                            'term'  => [
                                'start' => '2000-01-01 12:00:00Z',
                                'end'   => '3000-01-01 12:00:00Z',
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
                'eventId'     => $eventId,
                'segments'    => [
                    [
                        'price' => [
                            'amount'   => '200',
                            'currency' => 'RUB',
                        ],
                        'term'  => [
                            'start' => '2000-01-01 12:00:00Z',
                            'end'   => '3000-01-01 12:00:00Z',
                        ],
                    ],
                ],
                'productType' => 'ticket',
            ],
        ]);

        $promocode = 'FOO';
        $this->manager->createFixedPromocode([
            'eventId'          => $eventId,
            'code'             => $promocode,
            'discount'         => [
                'amount'   => '100',
                'currency' => 'RUB',
            ],
            'useLimit'         => 10,
            'expireAt'         => '3000-01-01 12:00:00Z',
            'usable'           => true,
            'allowedTariffIds' => [$tariffId]
        ]);
        $this->manager->seeFixedPromocodeInList($eventId, [
            'data' => [
                [
                    'id'             => '@uuid@',
                    'eventId'        => $eventId,
                    'code'           => $promocode,
                    'discount'       => [
                        'amount'   => '100',
                        'currency' => 'RUB',
                        'type'     => 'fixed',
                    ],
                    'useLimit'       => 10,
                    'expireAt'       => '3000-01-01 12:00:00Z',
                    'usable'         => true,
                    'usedInOrders'   => [
                        'order_ids' => [],
                    ],
                    'allowedTariffs' => [
                        'type'       => 'specific',
                        'tariff_ids' => [ // TODO camelCase
                            ['id' => $tariffId],
                        ]
                    ],
                ]
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
            'data' => [ // TODO currency and sum is a money type
                [
                    'id'        => $orderId,
                    'eventId'   => $eventId,
                    'tariffId'  => $tariffId,
                    'paid'      => false,
                    'sum'       => [
                        'amount'   => '200',
                        'currency' => 'RUB',
                    ],
                    'discount'  => null,
                    'total'     => [
                        'amount'   => '200',
                        'currency' => 'RUB',
                    ],
                    'cancelled' => false,
                    'userId'    => '@uuid@',
                    'makedAt'   => '@string@.isDateTime()',
                ],
            ]
        ]);
        $this->manager->seeOrderById($orderId, [
            'data' => [
                'id'        => $orderId,
                'eventId'   => $eventId,
                'tariffId'  => $tariffId,
                'paid'      => false,
                'sum'       => [
                    'amount'   => '200',
                    'currency' => 'RUB',
                ],
                'discount'  => null,
                'total'     => [
                    'amount'   => '200',
                    'currency' => 'RUB',
                ],
                'cancelled' => false,
                'userId'    => '@uuid@',
                'makedAt'   => '@string@.isDateTime()',
            ]
        ]);

        $this->visitor->usePromocode([
            'code'     => $promocode,
            'orderId'  => $orderId,
            'tariffId' => $tariffId,
        ]);
        $this->manager->seeOrderInList($eventId, [
            'data' => [
                [
                    'id'        => $orderId,
                    'eventId'   => $eventId,
                    'tariffId'  => $tariffId,
                    'paid'      => false,
                    'sum'       => [
                        'amount'   => '200',
                        'currency' => 'RUB',
                    ],
                    'discount'  => [
                        'amount'   => '100',
                        'currency' => 'RUB',
                    ],
                    'total'     => [
                        'amount'   => '100',
                        'currency' => 'RUB',
                    ],
                    'cancelled' => false,
                    'userId'    => '@uuid@',
                    'makedAt'   => '@string@.isDateTime()',
                ],
            ]
        ]);
        $this->manager->seeOrderById($orderId, [
            'data' => [
                'id'        => $orderId,
                'eventId'   => $eventId,
                'tariffId'  => $tariffId,
                'paid'      => false,
                'sum'       => [
                    'amount'   => '200',
                    'currency' => 'RUB',
                ],
                'discount'  => [
                    'amount'   => '100',
                    'currency' => 'RUB',
                ],
                'total'     => [
                    'amount'   => '100',
                    'currency' => 'RUB',
                ],
                'cancelled' => false,
                'userId'    => '@uuid@',
                'makedAt'   => '@string@.isDateTime()',
            ]
        ]);
        $this->manager->seeFixedPromocodeInList($eventId, [
            'data' => [
                [
                    'id'             => '@uuid@',
                    'eventId'        => $eventId,
                    'code'           => $promocode,
                    'discount'       => [
                        'amount'   => '100',
                        'currency' => 'RUB',
                        'type'     => 'fixed',
                    ],
                    'useLimit'       => 10,
                    'expireAt'       => '3000-01-01 12:00:00Z',
                    'usable'         => true,
                    'usedInOrders'   => [
                        'order_ids' => [
                            ['id' => $orderId],
                        ],
                    ],
                    'allowedTariffs' => [
                        'type'       => 'specific',
                        'tariff_ids' => [
                            ['id' => $tariffId],
                        ]
                    ],
                ]
            ]
        ]);

        $this->manager->markOrderPaid([
            'orderId' => $orderId,
            'eventId' => $eventId,
        ]);
        $this->visitor->receivesEmailWithTicket([
            'subject' => 'Thanks for buy ticket',
            'from'    => ['no-reply@event.com' => null],
            'to'      => ['john@email.com' => null],
        ]);
        $this->manager->seeOrderInList($eventId, [
            'data' => [
                [
                    'id'        => $orderId,
                    'eventId'   => $eventId,
                    'tariffId'  => $tariffId,
                    'paid'      => true,
                    'sum'       => [
                        'amount'   => '200',
                        'currency' => 'RUB',
                    ],
                    'discount'  => [
                        'amount'   => '100',
                        'currency' => 'RUB',
                    ],
                    'total'     => [
                        'amount'   => '100',
                        'currency' => 'RUB',
                    ],
                    'cancelled' => false,
                    'userId'    => '@uuid@',
                    'makedAt'   => '@string@.isDateTime()',
                ],
            ]
        ]);
        $this->manager->seeOrderById($orderId, [
            'data' => [
                'id'        => $orderId,
                'eventId'   => $eventId,
                'tariffId'  => $tariffId,
                'paid'      => true,
                'sum'       => [
                    'amount'   => '200',
                    'currency' => 'RUB',
                ],
                'discount'  => [
                    'amount'   => '100',
                    'currency' => 'RUB',
                ],
                'total'     => [
                    'amount'   => '100',
                    'currency' => 'RUB',
                ],
                'cancelled' => false,
                'userId'    => '@uuid@',
                'makedAt'   => '@string@.isDateTime()',
            ]
        ]);
        $ticketId = $this->manager->seeTicketInList($eventId, [
            'data' => [
                [
                    'id'        => '@uuid@',
                    'eventId'   => $eventId,
                    'number'    => '@string@',
                    'createdAt' => '@string@.isDateTime()',
                ]
            ]
        ]);
        $this->manager->seeTicketById($ticketId, [
            'data' => [
                'id'        => $ticketId,
                'eventId'   => $eventId,
                'number'    => '@string@',
                'createdAt' => '@string@.isDateTime()',
            ]
        ]);
    }

    /**
     * @test
     */
    public function byCard(): void
    {
        $eventId = $this->manager->createsEvent();
        $this->manager->createsEventDomain([
            'id'     => $eventId,
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
                        'start' => '2000-01-01 12:00:00Z',
                        'end'   => '3000-01-01 12:00:00Z',
                    ],
                ],
            ],
            'productType' => 'ticket',
        ]);
        $this->manager->seeTariffDetailsById($tariffId, [
            'data' => [
                'id'         => $tariffId,
                'tariffType' => 'silver_pass',
            ],
        ]);
        $this->manager->seeTariffInList($eventId, [
            'data' => [
                [
                    'id'          => $tariffId,
                    'eventId'     => $eventId,
                    'segments'    => [
                        [
                            'price' => [
                                'amount'   => '200',
                                'currency' => 'RUB',
                            ],
                            'term'  => [
                                'start' => '2000-01-01 12:00:00Z',
                                'end'   => '3000-01-01 12:00:00Z',
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
                'eventId'     => $eventId,
                'segments'    => [
                    [
                        'price' => [
                            'amount'   => '200',
                            'currency' => 'RUB',
                        ],
                        'term'  => [
                            'start' => '2000-01-01 12:00:00Z',
                            'end'   => '3000-01-01 12:00:00Z',
                        ],
                    ],
                ],
                'productType' => 'ticket',
            ]
        ]);

        $promocode = 'FOO';
        $this->manager->createFixedPromocode([
            'eventId'          => $eventId,
            'code'             => $promocode,
            'discount'         => [
                'amount'   => '100',
                'currency' => 'RUB',
            ],
            'useLimit'         => 10,
            'expireAt'         => '3000-01-01 12:00:00Z',
            'usable'           => true,
            'allowedTariffIds' => [$tariffId]
        ]);
        $this->manager->seeFixedPromocodeInList($eventId, [
            'data' => [
                [
                    'id'             => '@uuid@',
                    'eventId'        => $eventId,
                    'code'           => $promocode,
                    'discount'       => [
                        'amount'   => '100',
                        'currency' => 'RUB',
                        'type'     => 'fixed',
                    ],
                    'useLimit'       => 10,
                    'expireAt'       => '3000-01-01 12:00:00Z',
                    'usable'         => true,
                    'usedInOrders'   => [
                        'order_ids' => [],
                    ],
                    'allowedTariffs' => [
                        'type'       => 'specific',
                        'tariff_ids' => [ // TODO camelCase
                            ['id' => $tariffId],
                        ]
                    ],
                ]
            ]
        ]);

        $orderId = $this->visitor->placeOrder([
            'tariffId'  => $tariffId,
            'firstName' => 'john',
            'lastName'  => 'Doe',
            'email'     => 'john@email.com',
            'phone'     => '+123456789',
        ]);
        $this->manager->seeOrderInList($eventId, [
            'data' => [
                [
                    'id'        => $orderId,
                    'eventId'   => $eventId,
                    'tariffId'  => $tariffId,
                    'paid'      => false,
                    'sum'       => [
                        'amount'   => '200',
                        'currency' => 'RUB',
                    ],
                    'discount'  => null,
                    'total'     => [
                        'amount'   => '200',
                        'currency' => 'RUB',
                    ],
                    'cancelled' => false,
                    'userId'    => '@uuid@',
                    'makedAt'   => '@string@.isDateTime()',
                ],
            ]
        ]);
        $this->manager->seeOrderById($orderId, [
            'data' => [
                'id'        => $orderId,
                'eventId'   => $eventId,
                'tariffId'  => $tariffId,
                'paid'      => false,
                'sum'       => [
                    'amount'   => '200',
                    'currency' => 'RUB',
                ],
                'discount'  => null,
                'total'     => [
                    'amount'   => '200',
                    'currency' => 'RUB',
                ],
                'cancelled' => false,
                'userId'    => '@uuid@',
                'makedAt'   => '@string@.isDateTime()',
            ]
        ]);

        $this->visitor->usePromocode([
            'code'     => $promocode,
            'orderId'  => $orderId,
            'tariffId' => $tariffId,
        ]);
        $this->manager->seeOrderInList($eventId, [
            'data' => [
                [
                    'id'        => $orderId,
                    'eventId'   => $eventId,
                    'tariffId'  => $tariffId,
                    'paid'      => false,
                    'sum'       => [
                        'amount'   => '200',
                        'currency' => 'RUB',
                    ],
                    'discount'  => [
                        'amount'   => '100',
                        'currency' => 'RUB',
                    ],
                    'total'     => [
                        'amount'   => '100',
                        'currency' => 'RUB',
                    ],
                    'cancelled' => false,
                    'userId'    => '@uuid@',
                    'makedAt'   => '@string@.isDateTime()',
                ],
            ]
        ]);
        $this->manager->seeOrderById($orderId, [
            'data' => [
                'id'        => $orderId,
                'eventId'   => $eventId,
                'tariffId'  => $tariffId,
                'paid'      => false,
                'sum'       => [
                    'amount'   => '200',
                    'currency' => 'RUB',
                ],
                'discount'  => [
                    'amount'   => '100',
                    'currency' => 'RUB',
                ],
                'total'     => [
                    'amount'   => '100',
                    'currency' => 'RUB',
                ],
                'cancelled' => false,
                'userId'    => '@uuid@',
                'makedAt'   => '@string@.isDateTime()',
            ]
        ]);
        $this->manager->seeFixedPromocodeInList($eventId, [
            'data' => [
                [
                    'id'             => '@uuid@',
                    'eventId'        => $eventId,
                    'code'           => $promocode,
                    'discount'       => [
                        'amount'   => '100',
                        'currency' => 'RUB',
                        'type'     => 'fixed',
                    ],
                    'useLimit'       => 10,
                    'expireAt'       => '3000-01-01 12:00:00Z',
                    'usable'         => true,
                    'usedInOrders'   => [
                        'order_ids' => [
                            ['id' => $orderId],
                        ],
                    ],
                    'allowedTariffs' => [
                        'type'       => 'specific',
                        'tariff_ids' => [
                            ['id' => $tariffId],
                        ]
                    ],
                ]
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
                    'id'        => $orderId,
                    'eventId'   => $eventId,
                    'tariffId'  => $tariffId,
                    'paid'      => true,
                    'sum'       => [
                        'amount'   => '200',
                        'currency' => 'RUB',
                    ],
                    'discount'  => [
                        'amount'   => '100',
                        'currency' => 'RUB',
                    ],
                    'total'     => [
                        'amount'   => '100',
                        'currency' => 'RUB',
                    ],
                    'cancelled' => false,
                    'userId'    => '@uuid@',
                    'makedAt'   => '@string@.isDateTime()',
                ],
            ]
        ]);
        $this->manager->seeOrderById($orderId, [
            'data' => [
                'id'        => $orderId,
                'eventId'   => $eventId,
                'tariffId'  => $tariffId,
                'paid'      => true,
                'sum'       => [
                    'amount'   => '200',
                    'currency' => 'RUB',
                ],
                'discount'  => [
                    'amount'   => '100',
                    'currency' => 'RUB',
                ],
                'total'     => [
                    'amount'   => '100',
                    'currency' => 'RUB',
                ],
                'cancelled' => false,
                'userId'    => '@uuid@',
                'makedAt'   => '@string@.isDateTime()',
            ]
        ]);
        $ticketId = $this->manager->seeTicketInList($eventId, [
            'data' => [
                [
                    'id'        => '@uuid@',
                    'eventId'   => $eventId,
                    'number'    => '@string@',
                    'createdAt' => '@string@.isDateTime()',
                ]
            ]
        ]);
        $this->manager->seeTicketById($ticketId, [
            'data' => [
                'id'        => $ticketId,
                'eventId'   => $eventId,
                'number'    => '@string@',
                'createdAt' => '@string@.isDateTime()',
            ]
        ]);
    }
}
