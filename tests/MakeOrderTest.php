<?php

namespace App\Tests;

use App\Event\Model\Event;
use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Product\Model\Product;
use App\Product\Model\ProductId;
use App\Product\Model\ProductType;
use App\Product\Model\Ticket;
use App\Tariff\Model\TariffPriceNet;
use App\Tariff\Model\TariffSegment;
use App\Tariff\Model\TariffTerm;
use App\Tariff\Model\Tariff;
use App\Tariff\Model\TicketTariffId;
use App\User\Model\User;
use Coduo\PHPMatcher\PHPUnit\PHPMatcherAssertions;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Money\Currency;
use Money\Money;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MakeOrderTest extends WebTestCase
{
    use PHPMatcherAssertions;

    private const EVENT_ID = 'ac28bf81-08c6-4fc0-beae-7d4aabf1396e';

    private const SILVER_PASS_TARIFF_ID = '01419cd4-9302-4f2c-8b71-912c4dcf977f';

    private const SILVER_PASS_PRODUCT_ID = '2e9b2533-21d8-4f25-8adc-38ff83182956';

    private const SILVER_TICKET_TARIFF_STATUS = 'silver';

    private const EVENT_DOMAIN = '2019foo.event.com';

    public function testBuyTicketByWire(): void
    {
        $manager = new Manager(self::createClient());

        $manager->createsEvent([
            'name'   => '2019 foo event',
            'domain' => self::EVENT_DOMAIN,
        ]);
        $manager->seesEventCreated([
            'event_id' => '@uuid@',
            'name'     => '2019 foo event',
            'domain'   => self::EVENT_DOMAIN,
        ]);

        $visitor = new Visitor(self::createClient(), self::EVENT_DOMAIN);
        $visitor->placeOrder([
            'firstName'     => 'john',
            'lastName'      => 'Doe',
            'email'         => 'john@email.com',
            'paymentMethod' => 'wire', // TODO
            'tariffId'      => self::SILVER_PASS_TARIFF_ID,
            'phone'         => '+123456789',
        ]);
        $visitor->seeOrderPlaced(); //TODO move to place order ?

        $manager->seeOrderPlaced([
            'id'         => '@uuid@',
            'user_id'    => '@uuid@',
            'paid'       => false,
            'maked_at'   => '@string@.isDateTime()',
            'product'    => 'silver_pass',
            'phone'      => '+123456789',
            'first_name' => 'john',
            'last_name'  => 'Doe',
            'email'      => 'john@email.com',
            'sum'        => '200',
            'currency'   => 'RUB',
        ]);
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
        ]);

        /** @var EntityManagerInterface $em */
        $em    = static::$container->get('doctrine.orm.default_entity_manager');
        $event = new Event(EventId::fromString(self::EVENT_ID));
        $em->persist($event);

        $ticketTariff = $event->createTicketTariff(
            TicketTariffId::fromString(self::SILVER_PASS_TARIFF_ID),
            new TariffPriceNet([
                new TariffSegment(
                    new Money(200, new Currency('RUB')),
                    new TariffTerm(new DateTimeImmutable('-1 year'), new DateTimeImmutable('+1 year'))
                ),
            ]),
            ProductType::silverPass()
        );
        $em->persist($ticketTariff);

        $product = $ticketTariff->createProduct(ProductId::new());
        $em->persist($product);

        $em->flush();
    }
}
