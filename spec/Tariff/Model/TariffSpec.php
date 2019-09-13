<?php

namespace spec\App\Tariff\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\Product;
use App\Product\Model\ProductId;
use App\Tariff\Model\TariffId;
use App\Tariff\Model\TariffPriceNet;
use App\Tariff\Model\TariffSegment;
use App\Tariff\Model\TariffTerm;
use App\User\Model\User;
use DateTimeImmutable;
use Money\Currency;
use Money\Money;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TariffSpec extends ObjectBehavior
{
    public function it_should_be_part_of_the_order(Product $product, Order $order, User $user)
    {
        $user->beADoubleOf(User::class);
        $order->beADoubleOf(Order::class);
        $product->beADoubleOf(Product::class);
        $product
            ->makeOrder(Argument::cetera())
            ->willReturn($order)
        ;

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
            ])
        );

        $this
            ->makeOrder(
                OrderId::new(),
                $eventId,
                $product,
                new Money(100, new Currency('RUB')),
                $user,
                new DateTimeImmutable('now')
            )
            ->shouldReturnAnInstanceOf($order)
        ;
    }
}
