<?php

namespace spec\App\Tariff\Model;

use App\Model\Event\EventId;
use App\Model\Order\Order;
use App\Model\Order\OrderId;
use App\Model\Tariff\ProductType;
use App\Model\Tariff\TariffId;
use App\Model\Tariff\TariffPriceNet;
use App\Model\Tariff\TariffSegment;
use App\Model\Tariff\TariffTerm;
use App\Model\User\UserId;
use DateTimeImmutable;
use Money\Currency;
use Money\Money;
use PhpSpec\ObjectBehavior;

// TODO try to create id of the object inside constructor. Use factories to create objects with same id ?
class TariffSpec extends ObjectBehavior
{
    public function it_should_be_part_of_the_order(Order $order)
    {
        $order->beADoubleOf(Order::class);

        $eventId = EventId::new();
        $this->beConstructedWith(
            TariffId::new(),
            $eventId,
            new TariffPriceNet([
                new TariffSegment(
                    new Money(100, new Currency('RUB')),
                    new TariffTerm(
                        new DateTimeImmutable('now'),
                        new DateTimeImmutable('now')
                    )
                ),
            ]),
            ProductType::ticket()
        );

        $this
            ->makeOrder(
                OrderId::new(),
                $eventId,
                UserId::new(),
                new Money(100, new Currency('RUB')),
                new DateTimeImmutable('now')
            )
            ->shouldReturnAnInstanceOf(Order::class);
    }
}
