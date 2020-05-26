<?php

namespace spec\App\Tariff\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\Product;
use App\Product\Model\ProductType;
use App\Tariff\Model\Error\TariffAndOrderMustBeRelatedToSameEvent;
use App\Tariff\Model\TariffId;
use App\Tariff\Model\TariffPriceNet;
use App\Tariff\Model\TariffSegment;
use App\Tariff\Model\TariffTerm;
use App\User\Model\UserId;
use DateTimeImmutable;
use Money\Currency;
use Money\Money;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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

    public function it_should_not_be_part_of_the_order_which_is_not_related_to_same_event(Order $order)
    {
        $order->beADoubleOf(Order::class);
        $this->beConstructedWith(
            TariffId::new(),
            EventId::new(),
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
                EventId::new(),
                UserId::new(),
                new Money(100, new Currency('RUB')),
                new DateTimeImmutable('now')
            )
            ->shouldReturnAnInstanceOf(TariffAndOrderMustBeRelatedToSameEvent::class);
    }
}
