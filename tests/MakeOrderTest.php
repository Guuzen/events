<?php

namespace App\Tests;

use App\Event\Model\Event;
use App\Event\Model\EventConfig;
use App\Order\Model\Order;
use App\Product\Model\Product;
use App\Product\Model\Ticket;
use App\Promocode\Model\RegularPromocode;
use App\Tariff\Model\Tariff;
use App\User\Model\User;
use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MakeOrderTest extends WebTestCase
{
    use PHPMatcherAssertions;

    private const EVENT_ID = 'ac28bf81-08c6-4fc0-beae-7d4aabf1396e';

    private const SILVER_PASS_TARIFF_ID = '01419cd4-9302-4f2c-8b71-912c4dcf977f';

    private const SILVER_PASS_PRODUCT_ID = '2e9b2533-21d8-4f25-8adc-38ff83182956';

    private const SILVER_TICKET_TARIFF_STATUS = 'silver';

    private const EVENT_DOMAIN = '2019foo.event.com';

    // TODO no consistency between request camalcase and response underscore
    private function testBuyTicketByWire(): void
    {
        $manager = new Manager(self::createClient());

        $eventId = $manager->createsEvent([
            'name'   => '2019 foo event',
            'domain' => self::EVENT_DOMAIN,
        ]);

        $manager->seesEventCreated([
            'id'     => $eventId,
            'name'   => '2019 foo event',
            'domain' => self::EVENT_DOMAIN,
        ]);

        $tariffId = $manager->createsTariff([
            'event_id'     => $eventId,
            'product_type' => 'silver_pass',
            'price_net'    => [
                [
                    'price' => [
                        'amount'   => '200',
                        'currency' => 'RUB',
                    ],
                    'term' => [
                        'start' => (new DateTimeImmutable('-1 day'))->format('Y-m-d H:i:s'),
                        'end'   => (new DateTimeImmutable('+1 day'))->format('Y-m-d H:i:s'),
                    ],
                ],
            ],
        ]);
        $manager->seesTariffCreated([
            'id'            => $tariffId,
            'product_type'  => 'silver_pass',
            'price'         => '200 RUB',
            'term_start'    => '@string@.isDateTime()',
            'term_end'      => '@string@.isDateTime()',
        ], $eventId);

        // TODO createsRegularPromocode ?
        $promocodeId = $manager->createsPromocode([
            'event_id'  => $eventId,
            'discount'  => [
                'amount'   => '100',
                'currency' => 'RUB',
            ],
            'type'            => 'regular',
            'use_limit'       => 1,
            'expire_at'       => (new DateTimeImmutable('tomorrow'))->format('Y-m-d H:i:s'),
            'allowed_tariffs' => [$tariffId],
            'usable'          => true,
        ]);

//        $manager->seesPromocodeCreated([
//            'id'       => $promocodeId,
//            'eventId' => $eventId,
//            'discount' => [
//                'amount'   => '100',
//                'currency' => 'RUB',
//            ],
//            'type'           => 'regular',
//            'useLimit'       => 1,
//            'expireAt'       => (new DateTimeImmutable('tomorrow'))->format('Y-m-d H:i:s'),
//            'allowedTariffs' => [$tariffId],
//            'usable'         => true,
//        ], $eventId);

        $productId = $manager->createsTicket([
            'event_id'  => $eventId,
            'number'    => '10002000',
            'tariff_id' => $tariffId,
        ]);
        $manager->seesProductCreated([
            'type'        => 'silver_pass',
            'number'      => '10002000',
            'created_at'  => '@string@.isDateTime()',
            'reserved'    => false,
        ], $eventId);

        $visitor = new Visitor(self::createClient(), self::EVENT_DOMAIN);
        $visitor->placeOrder([
            'first_name'     => 'john',
            'last_name'      => 'Doe',
            'email'          => 'john@email.com',
            'payment_method' => 'wire', // TODO
            'tariff_id'      => $tariffId,
            'phone'          => '+123456789',
        ]);
        $visitor->seeOrderPlaced(); //TODO move to place order ?

        $manager->seesOrderPlaced([
            'user_id'      => '@uuid@',
            'id'           => '@uuid@',
            'product_id'   => $productId,
            'tariff_id'    => $tariffId,
            'paid'         => false,
            'maked_at'     => '@string@.isDateTime()',
            'product'      => 'silver_pass',
            'phone'        => '+123456789',
            'first_name'   => 'john',
            'last_name'    => 'Doe',
            'email'        => 'john@email.com',
            'sum'          => '200',
            'currency'     => 'RUB',
            'event_id'     => $eventId,
            'cancelled'    => false,
        ], $eventId);
    }

    // TODO extract this common method
    private function truncateTables(array $entities)
    {
        /** @var Connection $connection */
        $connection = static::$container->get('doctrine.dbal.default_connection');
        /** @var EntityManagerInterface $em */
        $em               = static::$container->get('doctrine.orm.default_entity_manager');
        $databasePlatform = $connection->getDatabasePlatform();

        foreach ($entities as $entity) {
            $query = $databasePlatform->getTruncateTableSQL(
                $em->getClassMetadata($entity)->getTableName()
            );
            $connection->executeUpdate($query);
        }
    }

    protected function setUp(): void
    {
        static::bootKernel();
        $this->truncateTables([
            Event::class,
            Tariff::class,
            Ticket::class,
            Order::class,
            User::class,
            Product::class,
            EventConfig::class,
            RegularPromocode::class,
        ]);
    }
}
