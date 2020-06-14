<?php

namespace spec\App\Order\Model;

use App\Event\Model\EventId;
use App\Fondy\CantGetPaymentUrl;
use App\Fondy\Fondy;
use App\Order\Model\Error\OrderAlreadyPaid;
use App\Order\Model\Exception\NotPossibleToApplyDiscountTwiceOnOrder;
use App\Order\Model\Exception\PromocodeAlreadyUsedInOrder;
use App\Order\Model\OrderId;
use App\Product\Model\ProductType;
use App\Promocode\Model\Discount\FixedDiscount;
use App\Promocode\Model\Promocode;
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

    private $productType;

    private $tariffId;

    private $userId;

    private $hundredRubles;

    private $now;

    public function let()
    {
        $this->orderId       = OrderId::new();
        $this->eventId       = EventId::new();
        $this->productType   = ProductType::ticket();
        $this->tariffId      = TariffId::new();
        $this->userId        = UserId::new();
        $this->hundredRubles = new Money('100', new Currency('RUB'));
        $this->now           = new DateTimeImmutable('now');
        $this->beConstructedWith(
            $this->orderId,
            $this->eventId,
            $this->productType,
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

    public function it_can_create_fondy_payment(Fondy $fondyGateway)
    {
        $paymentUrl = 'http://fondy.checkout.url';
        $fondyGateway->beADoubleOf(Fondy::class);
        $fondyGateway
            ->checkoutUrl($this->hundredRubles, $this->orderId)
            ->shouldBeCalledOnce()
            ->willReturn($paymentUrl);

        $this->createFondyPayment($fondyGateway)->shouldReturn($paymentUrl);
    }

    public function it_cant_create_fondy_payment_when_fondy_cant_get_payment_url(Fondy $fondy)
    {
        $error = new CantGetPaymentUrl();
        $fondy->beADoubleOf(Fondy::class);
        $fondy
            ->checkoutUrl(Argument::cetera())
            ->willReturn($error);

        $this->createFondyPayment($fondy)->shouldReturn($error);
    }

    public function it_should_calculate_total_price_with_discount_if_discount_applied(): void
    {
        $anyDiscount = new FixedDiscount(new Money('1', new Currency('RUB')));

        $this->applyDiscount($anyDiscount);

        $this->calculateTotal()->shouldBeLike(new Money('99', new Currency('RUB')));
    }

    public function it_should_not_be_possible_to_apply_discount_twice_on_order(): void
    {
        $anyDiscount = new FixedDiscount(new Money('1', new Currency('RUB')));

        $this->shouldNotThrow()->during('applyDiscount', [$anyDiscount]);
        $this->shouldThrow(NotPossibleToApplyDiscountTwiceOnOrder::class)->during('applyDiscount', [$anyDiscount]);
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
