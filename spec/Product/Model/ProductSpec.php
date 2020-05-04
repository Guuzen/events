<?php

namespace spec\App\Product\Model;

use App\Event\Model\EventId;
use App\Order\Model\OrderId;
use App\Product\Model\Error\OrderAndProductMustBeRelatedToSameEvent;
use App\Product\Model\Error\OrderAndProductMustBeRelatedToSameTariff;
use App\Product\Model\Error\ProductCantBeDeliveredIfNotReserved;
use App\Product\Model\Error\ProductCantBeReservedIfAlreadyReserved;
use App\Product\Model\Exception\ProductReserveCantBeCancelledIfAlreadyDelivered;
use App\Product\Model\ProductDelivered;
use App\Product\Model\ProductId;
use App\Product\Model\ProductType;
use App\Tariff\Model\TariffId;
use App\User\Model\UserId;
use DateTimeImmutable;
use Money\Currency;
use Money\Money;
use PhpSpec\ObjectBehavior;

class ProductSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            ProductId::new(),
            EventId::new(),
            TariffId::new(),
            ProductType::ticket(),
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

    public function it_should_be_deliverable_if_reserved()
    {
        $this->beConstructedWith(
            $productId = ProductId::new(),
            EventId::new(),
            TariffId::new(),
            $productType = ProductType::ticket(),
            new DateTimeImmutable(),
            $reserved = false
        );

        $now = new DateTimeImmutable('now');
        $this->reserve();
        $this->deliver($now)->shouldReturn(null);
        $this->releaseEvents()->shouldBeLike([new ProductDelivered($productId, $productType)]);
    }

    public function it_should_not_be_delivered_if_not_reserved()
    {
        $now = new DateTimeImmutable('now');
        $this->deliver($now)->shouldReturnAnInstanceOf(ProductCantBeDeliveredIfNotReserved::class);
    }

    public function its_reserve_cant_be_cancelled_if_already_delivered()
    {
        $now = new DateTimeImmutable('now');
        $this->reserve();
        $this->deliver($now);
        $this
            ->shouldThrow(ProductReserveCantBeCancelledIfAlreadyDelivered::class)
            ->during('cancelReserve');
    }

    public function it_should_not_be_part_of_the_order_which_is_not_related_to_same_event()
    {
        $tariffId = TariffId::new();
        $this->beConstructedWith(
            ProductId::new(),
            EventId::new(),
            $tariffId,
            ProductType::ticket(),
            new DateTimeImmutable('now')
        );

        $this->makeOrder(
            OrderId::new(),
            EventId::new(),
            $tariffId,
            UserId::new(),
            new Money(100, new Currency('RUB')),
            new DateTimeImmutable('now')
        )
            ->shouldReturnAnInstanceOf(OrderAndProductMustBeRelatedToSameEvent::class);
    }

    public function it_should_not_be_part_of_the_order_which_is_not_related_to_same_tariff()
    {
        $eventId = EventId::new();
        $this->beConstructedWith(
            ProductId::new(),
            $eventId,
            TariffId::new(),
            ProductType::ticket(),
            new DateTimeImmutable('now')
        );

        $this->makeOrder(
            OrderId::new(),
            $eventId,
            TariffId::new(),
            UserId::new(),
            new Money(100, new Currency('RUB')),
            new DateTimeImmutable('now')
        )
            ->shouldReturnAnInstanceOf(OrderAndProductMustBeRelatedToSameTariff::class);
    }

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
