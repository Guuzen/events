<?php

namespace spec\App\Product\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\Error\OrderProductMustBeRelatedToOrderEvent;
use App\Product\Model\Error\ProductCantBeDeliveredIfNotReserved;
use App\Product\Model\Error\ProductCantBeReservedIfAlreadyReserved;
use App\Product\Model\Exception\ProductReserveCantBeCancelledIfAlreadyDelivered;
use App\Product\Model\ProductId;
use App\Tariff\Model\TariffId;
use App\User\Model\User;
use DateTimeImmutable;
use Money\Currency;
use Money\Money;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProductSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            ProductId::new(),
            EventId::new(),
            TariffId::new(),
            new DateTimeImmutable(),
            $reserved = false
        );
    }

    public function it_should_be_possible_to_reserve()
    {
        $this->reserve()->shouldReturn(null);
    }

    public function it_should_not_be_possible_to_reserve_if_already_reserved()
    {
        $this->reserve();
        $this->reserve()->shouldReturnAnInstanceOf(ProductCantBeReservedIfAlreadyReserved::class);
    }

    public function it_should_be_delivered_if_reserved()
    {
        $now = new DateTimeImmutable('now');
        $this->reserve();
        $this->delivered($now)->shouldReturn(null);
    }

    public function it_should_not_be_delivered_if_not_reserved()
    {
        $now = new DateTimeImmutable('now');
        $this->delivered($now)->shouldReturnAnInstanceOf(ProductCantBeDeliveredIfNotReserved::class);
    }

    public function its_reserve_cant_be_cancelled_if_already_delivered()
    {
        $now = new DateTimeImmutable('now');
        $this->reserve();
        $this->delivered($now);
        $this
            ->shouldThrow(ProductReserveCantBeCancelledIfAlreadyDelivered::class)
            ->during('cancelReserve')
        ;
    }

    public function it_should_be_part_of_the_order(User $user, Order $order)
    {
        $order->beADoubleOf(Order::class);
        $user->beADoubleOf(User::class);
        $user
            ->makeOrder(Argument::cetera())
            ->willReturn($order)
        ;

        $eventId  = EventId::new();
        $tariffId = TariffId::new();
        $this->beConstructedWith(
            ProductId::new(),
            $eventId,
            $tariffId,
            new DateTimeImmutable('now')
        );

        $this->makeOrder(
            OrderId::new(),
            $eventId,
            $tariffId,
            new Money(100, new Currency('RUB')),
            $user,
            new DateTimeImmutable('now')
        );
    }

    public function it_should_not_be_part_of_the_order_which_is_not_related_to_same_event(User $user, Order $order)
    {
        $order->beADoubleOf(Order::class);
        $user->beADoubleOf(User::class);
        $user
            ->makeOrder(Argument::cetera())
            ->willReturn($order)
        ;

        $tariffId = TariffId::new();
        $this->beConstructedWith(
            ProductId::new(),
            EventId::new(),
            $tariffId,
            new DateTimeImmutable('now')
        );

        $this->makeOrder(
            OrderId::new(),
            EventId::new(),
            $tariffId,
            new Money(100, new Currency('RUB')),
            $user,
            new DateTimeImmutable('now')
        )
            ->shouldReturnAnInstanceOf(OrderProductMustBeRelatedToOrderEvent::class)
        ;
    }
//
//    public function it_should_not_be_possible_to_make_order_with_product_which_is_not_related_to_order_tariff(User $user, Order $order)
//    {
//        $order->beADoubleOf(Order::class);
//        $user->beADoubleOf(User::class);
//        $user
//            ->makeOrder(Argument::cetera())
//            ->willReturn($order)
//        ;
//
//        $eventId = EventId::new();
//        $this->makeOrder(
//            OrderId::new(),
//            $eventId,
//            TariffId::new(),
//            new Money(100, new Currency('RUB')),
//            $user,
//            new DateTimeImmutable('now')
//        )
//            ->shouldReturnAnInstanceOf(OrderProductMustBeRelatedToOrderTariff::class)
//        ;
//    }

//    public function it_should_be_possible_to_cancel_reserve()
//    {
//    }
//
//    public function it_should_not_be_possible_to_cancel_reserve_if_reserve_cancelled()
//    {
//    }
//
//    public function it_should_not_be_possible_to_cancel_reserve_if_not_reserved()
//    {

//    }
}
