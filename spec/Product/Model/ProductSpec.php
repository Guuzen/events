<?php

namespace spec\App\Product\Model;

use App\Event\Model\EventId;
use App\Product\Model\Exception\ProductReserveCantBeCancelledIfAlreadyDelivered;
use App\Product\Model\Exception\ProductCantBeDeliveredIfNotReserved;
use App\Product\Model\Exception\ProductCantBeReservedIfAlreadyReserved;
use App\Product\Model\ProductId;
use App\Product\Model\ProductType;
use DateTimeImmutable;
use PhpSpec\ObjectBehavior;

class ProductSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(
            ProductId::new(),
            EventId::new(),
            ProductType::silverPass(),
            new DateTimeImmutable(),
            $reserved = false
        );
    }

    public function it_should_be_possible_to_reserve()
    {
        $this
            ->shouldNotThrow()
            ->during('reserve')
        ;
    }

    public function it_should_not_be_possible_to_reserve_if_already_reserved()
    {
        $this->reserve();
        $this
            ->shouldThrow(ProductCantBeReservedIfAlreadyReserved::class)
            ->during('reserve')
        ;
    }

    public function it_should_be_delivered_if_reserved()
    {
        $now = new DateTimeImmutable('now');
        $this->reserve();
        $this
            ->shouldNotThrow()
            ->during('delivered', [$now])
        ;
    }

    public function it_should_not_be_delivered_if_not_reserved()
    {
        $now = new DateTimeImmutable('now');
        $this
            ->shouldThrow(ProductCantBeDeliveredIfNotReserved::class)
            ->during('delivered', [$now])
        ;
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

    public function it_should_be_possible_to_cancel_reserve()
    {
    }

    public function it_should_not_be_possible_to_cancel_reserve_if_reserve_cancelled()
    {
    }

    public function it_should_not_be_possible_to_cancel_reserve_if_not_reserved()
    {
    }
}
