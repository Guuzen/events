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
            'event_id'     => $eventId,
            'segments'     => [ // TODO change to priceNet
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
            'product_type' => 'ticket',
        ]);
        $this->manager->createTariffDescription([
            'id'          => $tariffId,
            'tariff_type' => 'silver_pass',
        ]);
        $this->manager->seeTariffDescriptionById($tariffId, [
            'data' => [
                'id'          => $tariffId,
                'tariff_type' => 'silver_pass',
            ],
        ]);
        $this->manager->seeTariffInList($eventId, [
            'data' => [
                [
                    'id'           => $tariffId,
                    'event_id'     => $eventId,
                    'price_net'    => [
                        [
                            'price' => [
                                'amount'   => '200',
                                'currency' => 'RUB',
                            ],
                            'term'  => [
                                'start' => '2000-01-01T12:00:00+00:00',
                                'end'   => '3000-01-01T12:00:00+00:00',
                            ],
                        ],
                    ],
                    'product_type' => 'ticket',
                ],
            ]
        ]);
        $this->manager->seeTariffById($tariffId, [
            'data' => [
                'id'           => $tariffId,
                'event_id'     => $eventId,
                'price_net'    => [
                    [
                        'price' => [
                            'amount'   => '200',
                            'currency' => 'RUB',
                        ],
                        'term'  => [
                            'start' => '2000-01-01T12:00:00+00:00',
                            'end'   => '3000-01-01T12:00:00+00:00',
                        ],
                    ],
                ],
                'product_type' => 'ticket',
            ],
        ]);

        $promocode = 'FOO';
        $this->manager->createFixedPromocode([
            'event_id'           => $eventId,
            'code'               => $promocode,
            'discount'           => [
                'amount'   => '100',
                'currency' => 'RUB',
            ],
            'use_limit'          => 10,
            'expire_at'          => '3000-01-01 12:00:00Z',
            'usable'             => true,
            'allowed_tariff_ids' => [$tariffId]
        ]);
        $this->manager->seeFixedPromocodeInList($eventId, [
            'data' => [
                [
                    'id'              => '@uuid@',
                    'event_id'        => $eventId,
                    'code'            => $promocode,
                    'discount'        => [
                        'amount' => [
                            'amount'   => '100',
                            'currency' => 'RUB',
                        ],
                        'type'   => 'fixed',
                    ],
                    'use_limit'       => 10,
                    'expire_at'       => '3000-01-01T12:00:00+00:00',
                    'usable'          => true,
                    'used_in_orders'  => [],
                    'allowed_tariffs' => [
                        'type'       => 'specific',
                        'tariff_ids' => [
                            $tariffId,
                        ]
                    ],
                ]
            ]
        ]);

        $orderId = $this->visitor->placeOrder([
            'tariff_id'  => $tariffId,
            'first_name' => 'john',
            'last_name'  => 'Doe',
            'email'      => 'john@email.com',
            'phone'      => '+123456789',
        ]);
        $this->visitor->awaitsForEmailWithTicket();
        $this->manager->seeOrderInList($eventId, [
            'data' => [ // TODO currency and sum is a money type
                [
                    'id'           => $orderId,
                    'event_id'     => $eventId,
                    'tariff_id'    => $tariffId,
                    'paid'         => false,
                    'sum'          => [
                        'amount'   => '200',
                        'currency' => 'RUB',
                    ],
                    'discount'     => null,
                    'total'        => [
                        'amount'   => '200',
                        'currency' => 'RUB',
                    ],
                    'cancelled'    => false,
                    'user_id'      => '@uuid@',
                    'product_type' => 'ticket',
                    'maked_at'     => '@string@.isDateTime()',
                ],
            ]
        ]);
        $this->manager->seeOrderById($orderId, [
            'data' => [
                'id'           => $orderId,
                'event_id'     => $eventId,
                'tariff_id'    => $tariffId,
                'paid'         => false,
                'sum'          => [
                    'amount'   => '200',
                    'currency' => 'RUB',
                ],
                'discount'     => null,
                'total'        => [
                    'amount'   => '200',
                    'currency' => 'RUB',
                ],
                'cancelled'    => false,
                'user_id'      => '@uuid@',
                'product_type' => 'ticket',
                'maked_at'     => '@string@.isDateTime()',
            ]
        ]);

        $this->visitor->usePromocode([
            'code'      => $promocode,
            'order_id'  => $orderId,
            'tariff_id' => $tariffId,
        ]);
        $this->manager->seeOrderInList($eventId, [
            'data' => [
                [
                    'id'           => $orderId,
                    'event_id'     => $eventId,
                    'tariff_id'    => $tariffId,
                    'paid'         => false,
                    'sum'          => [
                        'amount'   => '200',
                        'currency' => 'RUB',
                    ],
                    'discount'     => [
                        'type'   => 'fixed',
                        'amount' => [
                            'amount'   => '100',
                            'currency' => 'RUB',
                        ],
                    ],
                    'total'        => [
                        'amount'   => '100',
                        'currency' => 'RUB',
                    ],
                    'cancelled'    => false,
                    'user_id'      => '@uuid@',
                    'product_type' => 'ticket',
                    'maked_at'     => '@string@.isDateTime()',
                ],
            ]
        ]);
        $this->manager->seeOrderById($orderId, [
            'data' => [
                'id'           => $orderId,
                'event_id'     => $eventId,
                'tariff_id'    => $tariffId,
                'paid'         => false,
                'sum'          => [
                    'amount'   => '200',
                    'currency' => 'RUB',
                ],
                'discount'     => [
                    'type'   => 'fixed',
                    'amount' => [
                        'amount'   => '100',
                        'currency' => 'RUB',
                    ],
                ],
                'total'        => [
                    'amount'   => '100',
                    'currency' => 'RUB',
                ],
                'cancelled'    => false,
                'user_id'      => '@uuid@',
                'product_type' => 'ticket',
                'maked_at'     => '@string@.isDateTime()',
            ]
        ]);
        $this->manager->seeFixedPromocodeInList($eventId, [
            'data' => [
                [
                    'id'              => '@uuid@',
                    'event_id'        => $eventId,
                    'code'            => $promocode,
                    'discount'        => [
                        'amount' => [
                            'amount'   => '100',
                            'currency' => 'RUB',
                        ],
                        'type'   => 'fixed',
                    ],
                    'use_limit'       => 10,
                    'expire_at'       => '3000-01-01T12:00:00+00:00',
                    'usable'          => true,
                    'used_in_orders'  => [
                        $orderId,
                    ],
                    'allowed_tariffs' => [
                        'type'       => 'specific',
                        'tariff_ids' => [
                            $tariffId,
                        ]
                    ],
                ]
            ]
        ]);

        $this->manager->markOrderPaid($orderId, [
            'event_id' => $eventId,
        ]);
        $this->visitor->receivesEmailWithTicket([
            'subject' => 'Thanks for buy ticket',
            'from'    => ['no-reply@event.com' => null],
            'to'      => ['john@email.com' => null],
        ]);
        $this->manager->seeOrderInList($eventId, [
            'data' => [
                [
                    'id'           => $orderId,
                    'event_id'     => $eventId,
                    'tariff_id'    => $tariffId,
                    'paid'         => true,
                    'sum'          => [
                        'amount'   => '200',
                        'currency' => 'RUB',
                    ],
                    'discount'     => [
                        'type'   => 'fixed',
                        'amount' => [
                            'amount'   => '100',
                            'currency' => 'RUB',
                        ],
                    ],
                    'total'        => [
                        'amount'   => '100',
                        'currency' => 'RUB',
                    ],
                    'cancelled'    => false,
                    'user_id'      => '@uuid@',
                    'product_type' => 'ticket',
                    'maked_at'     => '@string@.isDateTime()',
                ],
            ]
        ]);
        $this->manager->seeOrderById($orderId, [
            'data' => [
                'id'           => $orderId,
                'event_id'     => $eventId,
                'tariff_id'    => $tariffId,
                'paid'         => true,
                'sum'          => [
                    'amount'   => '200',
                    'currency' => 'RUB',
                ],
                'discount'     => [
                    'type'   => 'fixed',
                    'amount' => [
                        'amount'   => '100',
                        'currency' => 'RUB',
                    ],
                ],
                'total'        => [
                    'amount'   => '100',
                    'currency' => 'RUB',
                ],
                'cancelled'    => false,
                'user_id'      => '@uuid@',
                'product_type' => 'ticket',
                'maked_at'     => '@string@.isDateTime()',
            ]
        ]);
        $ticketId = $this->manager->seeTicketInList($eventId, [
            'data' => [
                [
                    'id'         => '@uuid@',
                    'order_id'   => $orderId,
                    'event_id'   => $eventId,
                    'number'     => '@string@',
                    'created_at' => '@string@.isDateTime()',
                ]
            ]
        ]);
        $this->manager->seeTicketById($ticketId, [
            'data' => [
                'id'         => $ticketId,
                'order_id'   => $orderId,
                'event_id'   => $eventId,
                'number'     => '@string@',
                'created_at' => '@string@.isDateTime()',
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
            'event_id'     => $eventId,
            'segments'     => [
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
            'product_type' => 'ticket',
        ]);
        $this->manager->createTariffDescription([
            'id'          => $tariffId,
            'tariff_type' => 'silver_pass',
        ]);
        $this->manager->seeTariffDescriptionById($tariffId, [
            'data' => [
                'id'          => $tariffId,
                'tariff_type' => 'silver_pass',
            ],
        ]);
        $this->manager->seeTariffInList($eventId, [
            'data' => [
                [
                    'id'           => $tariffId,
                    'event_id'     => $eventId,
                    'price_net'    => [
                        [
                            'price' => [
                                'amount'   => '200',
                                'currency' => 'RUB',
                            ],
                            'term'  => [
                                'start' => '2000-01-01T12:00:00+00:00',
                                'end'   => '3000-01-01T12:00:00+00:00',
                            ],
                        ],
                    ],
                    'product_type' => 'ticket',
                ],
            ]
        ]);
        $this->manager->seeTariffById($tariffId, [
            'data' => [
                'id'           => $tariffId,
                'event_id'     => $eventId,
                'price_net'    => [
                    [
                        'price' => [
                            'amount'   => '200',
                            'currency' => 'RUB',
                        ],
                        'term'  => [
                            'start' => '2000-01-01T12:00:00+00:00',
                            'end'   => '3000-01-01T12:00:00+00:00',
                        ],
                    ],
                ],
                'product_type' => 'ticket',
            ]
        ]);

        $promocode = 'FOO';
        $this->manager->createFixedPromocode([
            'event_id'           => $eventId,
            'code'               => $promocode,
            'discount'           => [
                'amount'   => '100',
                'currency' => 'RUB',
            ],
            'use_limit'          => 10,
            'expire_at'          => '3000-01-01 12:00:00Z',
            'usable'             => true,
            'allowed_tariff_ids' => [$tariffId]
        ]);
        $this->manager->seeFixedPromocodeInList($eventId, [
            'data' => [
                [
                    'id'              => '@uuid@',
                    'event_id'        => $eventId,
                    'code'            => $promocode,
                    'discount'        => [
                        'amount' => [
                            'amount'   => '100',
                            'currency' => 'RUB',
                        ],
                        'type'   => 'fixed',
                    ],
                    'use_limit'       => 10,
                    'expire_at'       => '3000-01-01T12:00:00+00:00',
                    'usable'          => true,
                    'used_in_orders'  => [],
                    'allowed_tariffs' => [
                        'type'       => 'specific',
                        'tariff_ids' => [
                            $tariffId,
                        ]
                    ],
                ]
            ]
        ]);

        $orderId = $this->visitor->placeOrder([
            'tariff_id'  => $tariffId,
            'first_name' => 'john',
            'last_name'  => 'Doe',
            'email'      => 'john@email.com',
            'phone'      => '+123456789',
        ]);
        $this->manager->seeOrderInList($eventId, [
            'data' => [
                [
                    'id'           => $orderId,
                    'event_id'     => $eventId,
                    'tariff_id'    => $tariffId,
                    'paid'         => false,
                    'sum'          => [
                        'amount'   => '200',
                        'currency' => 'RUB',
                    ],
                    'discount'     => null,
                    'total'        => [
                        'amount'   => '200',
                        'currency' => 'RUB',
                    ],
                    'cancelled'    => false,
                    'user_id'      => '@uuid@',
                    'product_type' => 'ticket',
                    'maked_at'     => '@string@.isDateTime()',
                ],
            ]
        ]);
        $this->manager->seeOrderById($orderId, [
            'data' => [
                'id'           => $orderId,
                'event_id'     => $eventId,
                'tariff_id'    => $tariffId,
                'paid'         => false,
                'sum'          => [
                    'amount'   => '200',
                    'currency' => 'RUB',
                ],
                'discount'     => null,
                'total'        => [
                    'amount'   => '200',
                    'currency' => 'RUB',
                ],
                'cancelled'    => false,
                'user_id'      => '@uuid@',
                'product_type' => 'ticket',
                'maked_at'     => '@string@.isDateTime()',
            ]
        ]);

        $this->visitor->usePromocode([
            'code'      => $promocode,
            'order_id'  => $orderId,
            'tariff_id' => $tariffId,
        ]);
        $this->manager->seeOrderInList($eventId, [
            'data' => [
                [
                    'id'           => $orderId,
                    'event_id'     => $eventId,
                    'tariff_id'    => $tariffId,
                    'paid'         => false,
                    'sum'          => [
                        'amount'   => '200',
                        'currency' => 'RUB',
                    ],
                    'discount'     => [
                        'amount' => [
                            'amount'   => '100',
                            'currency' => 'RUB',
                        ],
                        'type'   => 'fixed',
                    ],
                    'total'        => [
                        'amount'   => '100',
                        'currency' => 'RUB',
                    ],
                    'cancelled'    => false,
                    'user_id'      => '@uuid@',
                    'product_type' => 'ticket',
                    'maked_at'     => '@string@.isDateTime()',
                ],
            ]
        ]);
        $this->manager->seeOrderById($orderId, [
            'data' => [
                'id'           => $orderId,
                'event_id'     => $eventId,
                'tariff_id'    => $tariffId,
                'paid'         => false,
                'sum'          => [
                    'amount'   => '200',
                    'currency' => 'RUB',
                ],
                'discount'     => [
                    'amount' => [
                        'amount'   => '100',
                        'currency' => 'RUB',
                    ],
                    'type'   => 'fixed',
                ],
                'total'        => [
                    'amount'   => '100',
                    'currency' => 'RUB',
                ],
                'cancelled'    => false,
                'user_id'      => '@uuid@',
                'product_type' => 'ticket',
                'maked_at'     => '@string@.isDateTime()',
            ]
        ]);
        $this->manager->seeFixedPromocodeInList($eventId, [
            'data' => [
                [
                    'id'              => '@uuid@',
                    'event_id'        => $eventId,
                    'code'            => $promocode,
                    'discount'        => [
                        'amount' => [
                            'amount'   => '100',
                            'currency' => 'RUB',
                        ],
                        'type'   => 'fixed',
                    ],
                    'use_limit'       => 10,
                    'expire_at'       => '3000-01-01T12:00:00+00:00',
                    'usable'          => true,
                    'used_in_orders'  => [
                        $orderId,
                    ],
                    'allowed_tariffs' => [
                        'type'       => 'specific',
                        'tariff_ids' => [
                            $tariffId,
                        ]
                    ],
                ]
            ]
        ]);

        $this->visitor->payOrderByCard($orderId);
        $this->visitor->awaitsForEmailWithTicket();
        $this->fondy->orderPaid($orderId);
        $this->visitor->receivesEmailWithTicket([
            'subject' => 'Thanks for buy ticket',
            'from'    => ['no-reply@event.com' => null],
            'to'      => ['john@email.com' => null],
        ]);

        $this->manager->seeOrderInList($eventId, [
            'data' => [
                [
                    'id'           => $orderId,
                    'event_id'     => $eventId,
                    'tariff_id'    => $tariffId,
                    'paid'         => true,
                    'sum'          => [
                        'amount'   => '200',
                        'currency' => 'RUB',
                    ],
                    'discount'     => [
                        'amount' => [
                            'amount'   => '100',
                            'currency' => 'RUB',
                        ],
                        'type'   => 'fixed',
                    ],
                    'total'        => [
                        'amount'   => '100',
                        'currency' => 'RUB',
                    ],
                    'cancelled'    => false,
                    'user_id'      => '@uuid@',
                    'product_type' => 'ticket',
                    'maked_at'     => '@string@.isDateTime()',
                ],
            ]
        ]);
        $this->manager->seeOrderById($orderId, [
            'data' => [
                'id'           => $orderId,
                'event_id'     => $eventId,
                'tariff_id'    => $tariffId,
                'paid'         => true,
                'sum'          => [
                    'amount'   => '200',
                    'currency' => 'RUB',
                ],
                'discount'     => [
                    'amount' => [
                        'amount'   => '100',
                        'currency' => 'RUB',
                    ],
                    'type'   => 'fixed',
                ],
                'total'        => [
                    'amount'   => '100',
                    'currency' => 'RUB',
                ],
                'cancelled'    => false,
                'user_id'      => '@uuid@',
                'product_type' => 'ticket',
                'maked_at'     => '@string@.isDateTime()',
            ]
        ]);
        $ticketId = $this->manager->seeTicketInList($eventId, [
            'data' => [
                [
                    'id'         => '@uuid@',
                    'order_id'   => $orderId,
                    'event_id'   => $eventId,
                    'number'     => '@string@',
                    'created_at' => '@string@.isDateTime()',
                ]
            ]
        ]);
        $this->manager->seeTicketById($ticketId, [
            'data' => [
                'id'         => $ticketId,
                'order_id'   => $orderId,
                'event_id'   => $eventId,
                'number'     => '@string@',
                'created_at' => '@string@.isDateTime()',
            ]
        ]);
    }
}
