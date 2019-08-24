<?php

namespace spec\App\Order\Model;

use App\Common\Result\Ok;
use App\Event\Model\EventId;
use App\Order\Model\Error\OrderAlreadyPaid;
use App\Order\Model\OrderId;
use App\Product\Model\ProductId;
use App\Tariff\Model\TariffId;
use App\User\Model\UserId;
use DateTimeImmutable;
use Money\Currency;
use Money\Money;
use PhpSpec\ObjectBehavior;

class OrderSpec extends ObjectBehavior
{
    public function let()
    {
        $orderId       = OrderId::new();
        $eventId       = EventId::new();
        $productId     = ProductId::new();
        $tariffId      = TariffId::new();
        $userId        = UserId::new();
        $hundredRubles = new Money('100', new Currency('RUB'));
        $now           = new DateTimeImmutable('now');
        $this->beConstructedWith(
            $orderId,
            $eventId,
            $productId,
            $tariffId,
            $userId,
            $hundredRubles,
            $now
        );
    }

    public function it_should_be_possible_to_mark_paid()
    {
        $this->markPaid()->shouldReturnAnInstanceOf(Ok::class);
    }

    public function it_should_not_be_possible_to_mark_paid_if_already_paid()
    {
        $this->markPaid();
        $this->markPaid()->shouldReturnAnInstanceOf(OrderAlreadyPaid::class);
    }

    public function it_should_not_be_possible_to_cancel_if_paid()
    {
    }

    public function it_should_not_be_possible_to_apply_promocode_if_paid()
    {
    }

//    function it_should_be_cancelled()
//    {
//        $orderId   = new OrderId();
//        $eventId     = new EventId();
//        $userId      = new UserId();
//        $tariffId    = new TariffId();
//        $promocodeId = new PromocodeId();
//        $sum         = new Money(0, new Currency('RUB'));
//        $createdNow  = new DateTimeImmutable('now');
//        $paid        = true;
//        $this->beConstructedWith($orderId, $eventId, $userId, $tariffId, $promocodeId, $sum, $createdNow, $paid);
//
//        $this
//            ->shouldNotThrow()
//            ->during('cancel')
//        ;
//    }
//
//    function it_should_not_be_cancelled_if_already_cancelled()
//    {
//        $orderId   = new OrderId();
//        $eventId     = new EventId();
//        $userId      = new UserId();
//        $tariffId    = new TariffId();
//        $promocodeId = new PromocodeId();
//        $sum         = new Money(0, new Currency('RUB'));
//        $createdNow  = new DateTimeImmutable('now');
//        $paid        = true;
//        $this->beConstructedWith($orderId, $eventId, $userId, $tariffId, $promocodeId, $sum, $createdNow, $paid);
//
//        $this->cancel();
//        $this
//            ->shouldThrow(OrderCancelled::class)
//            ->during('cancel')
//        ;
//    }
}
