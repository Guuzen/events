<?php

namespace spec\App\Order\Model;

use App\Event\Model\EventId;
use App\Fondy\FondyGateway;
use App\Order\Model\Error\OrderAlreadyPaid;
use App\Order\Model\OrderId;
use App\Product\Model\ProductId;
use App\Tariff\Model\TariffId;
use App\User\Model\UserId;
use DateTimeImmutable;
use Money\Currency;
use Money\Money;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OrderSpec extends ObjectBehavior
{
    private $orderId;

    private $eventId;

    private $productId;

    private $tariffId;

    private $userId;

    private $hundredRubles;

    private $now;

    public function let()
    {
        $this->orderId       = OrderId::new();
        $this->eventId       = EventId::new();
        $this->productId     = ProductId::new();
        $this->tariffId      = TariffId::new();
        $this->userId        = UserId::new();
        $this->hundredRubles = new Money('100', new Currency('RUB'));
        $this->now           = new DateTimeImmutable('now');
        $this->beConstructedWith(
            $this->orderId,
            $this->eventId,
            $this->productId,
            $this->tariffId,
            $this->userId,
            $this->hundredRubles,
            $this->now
        );
    }

    public function it_should_be_possible_to_mark_paid()
    {
        $this->markPaid()->shouldReturn(null);
    }

    public function it_should_not_be_possible_to_mark_paid_if_already_paid()
    {
        $this->markPaid();
        $this->markPaid()->shouldReturnAnInstanceOf(OrderAlreadyPaid::class);
    }

    public function it_can_be_paid_by_card(FondyGateway $fondyGateway)
    {
        $paymentUrl = 'http://some.payment.url/';
        $fondyGateway->beADoubleOf(FondyGateway::class);
        $fondyGateway
            ->checkoutUrl($this->hundredRubles, $this->orderId)
            ->shouldBeCalledOnce()
            ->willReturn($paymentUrl)
        ;

        $this->payByFondy($fondyGateway)->shouldReturn($paymentUrl);
    }

    public function it_can_not_be_paid_when_fondy_gateway_can_not_get_payment_url(FondyGateway $fondyGateway)
    {
        $error = new CanNotGetPaymentUrl();
        $fondyGateway->beADoubleOf(FondyGateway::class);
        $fondyGateway
            ->checkoutUrl($this->hundredRubles, $this->orderId)
            ->shouldBeCalledOnce()
            ->willReturn($error)
        ;

        $this->payByFondy($fondyGateway)->shouldReturn($error);
    }

//    public function it_should_not_be_possible_to_cancel_if_paid()
//    {
//    }
//
//    public function it_should_not_be_possible_to_apply_promocode_if_paid()
//    {
//    }

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
