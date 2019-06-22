<?php

namespace spec\App\Order\Model;

use App\Cloudpayments\PaymentStatus;
use App\Event\Model\EventId;
use App\Order\Model\Exception\OrderAlreadyPaid;
use App\Order\Model\Exception\OrderCancelled;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Promocode\Model\PromocodeId;
use App\Tariff\Model\TariffId;
use App\User\Model\UserId;
use DateTimeImmutable;
use Money\Currency;
use Money\Money;
use PhpSpec\ObjectBehavior;

class OrderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $orderId   = new OrderId();
        $eventId     = new EventId();
        $userId      = new UserId();
        $tariffId    = new TariffId();
        $promocodeId = new PromocodeId();
        $sum         = new Money(0, new Currency('RUB'));
        $createdNow  = new DateTimeImmutable('now');
        $notPaid     = false;
        $this->beConstructedWith($orderId, $eventId, $userId, $tariffId, $promocodeId, $sum, $createdNow, $notPaid);

        $this->shouldHaveType(Order::class);
    }

    function it_should_create_cloudpayments_payment()
    {
        $orderId   = new OrderId();
        $eventId     = new EventId();
        $userId      = new UserId();
        $tariffId    = new TariffId();
        $promocodeId = new PromocodeId();
        $sum         = new Money(0, new Currency('RUB'));
        $createdNow  = new DateTimeImmutable('now');
        $notPaid     = false;
        $this->beConstructedWith($orderId, $eventId, $userId, $tariffId, $promocodeId, $sum, $createdNow, $notPaid);

        $this
            ->shouldNotThrow()
            ->during('createCloudpaymentsPayment', ['foo', PaymentStatus::pending()])
        ;
    }

    function it_should_not_create_cloudpayments_payment_for_paid_order()
    {
        $orderId   = new OrderId();
        $eventId     = new EventId();
        $userId      = new UserId();
        $tariffId    = new TariffId();
        $promocodeId = new PromocodeId();
        $sum         = new Money(0, new Currency('RUB'));
        $createdNow  = new DateTimeImmutable('now');
        $paid        = true;
        $this->beConstructedWith($orderId, $eventId, $userId, $tariffId, $promocodeId, $sum, $createdNow, $paid);

        $this
            ->shouldThrow(OrderAlreadyPaid::class)
            ->during('createCloudpaymentsPayment', ['foo', PaymentStatus::pending()])
        ;
    }

    function it_should_not_create_cloudpayments_payment_for_cancelled_order()
    {
        $orderId   = new OrderId();
        $eventId     = new EventId();
        $userId      = new UserId();
        $tariffId    = new TariffId();
        $promocodeId = new PromocodeId();
        $sum         = new Money(0, new Currency('RUB'));
        $createdNow  = new DateTimeImmutable('now');
        $paid        = false;
        $this->beConstructedWith($orderId, $eventId, $userId, $tariffId, $promocodeId, $sum, $createdNow, $paid);

        $this->cancel();
        $this
            ->shouldThrow(OrderCancelled::class)
            ->during('createCloudpaymentsPayment', ['foo', PaymentStatus::pending()])
        ;
    }

    function it_should_be_cancelled()
    {
        $orderId   = new OrderId();
        $eventId     = new EventId();
        $userId      = new UserId();
        $tariffId    = new TariffId();
        $promocodeId = new PromocodeId();
        $sum         = new Money(0, new Currency('RUB'));
        $createdNow  = new DateTimeImmutable('now');
        $paid        = true;
        $this->beConstructedWith($orderId, $eventId, $userId, $tariffId, $promocodeId, $sum, $createdNow, $paid);

        $this
            ->shouldNotThrow()
            ->during('cancel')
        ;
    }

    function it_should_not_be_cancelled_if_already_cancelled()
    {
        $orderId   = new OrderId();
        $eventId     = new EventId();
        $userId      = new UserId();
        $tariffId    = new TariffId();
        $promocodeId = new PromocodeId();
        $sum         = new Money(0, new Currency('RUB'));
        $createdNow  = new DateTimeImmutable('now');
        $paid        = true;
        $this->beConstructedWith($orderId, $eventId, $userId, $tariffId, $promocodeId, $sum, $createdNow, $paid);

        $this->cancel();
        $this
            ->shouldThrow(OrderCancelled::class)
            ->during('cancel')
        ;
    }
}
