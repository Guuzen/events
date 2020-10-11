<?php

namespace spec\App\Order\Model;

use App\Event\Model\EventId;
use App\Fondy\CantGetPaymentUrl;
use App\Fondy\Fondy;
use App\Order\Model\Exception\NotPossibleToApplyPromocodeTwiceOnOrder;
use App\Order\Model\Exception\OrderAlreadyPaid;
use App\Order\Model\OrderId;
use App\Promocode\Model\Discount\FixedDiscount;
use App\Promocode\Model\PromocodeId;
use App\Tariff\Model\ProductType;
use App\Tariff\Model\TariffId;
use App\User\Model\UserId;
use DateTimeImmutable;
use Money\Currency;
use Money\Money;
use PhpSpec\ObjectBehavior;

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

    public function it_should_not_be_possible_to_mark_paid_if_already_paid()
    {
        $this->markPaid();
        $this->shouldThrow(OrderAlreadyPaid::class)->during('markPaid');
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

    public function it_should_not_be_possible_to_apply_promocode_twice_on_order(): void
    {
        $promocodeId = PromocodeId::new();

        $this->shouldNotThrow()->during('applyPromocode', [$promocodeId]);
        $this->shouldThrow(NotPossibleToApplyPromocodeTwiceOnOrder::class)->during('applyPromocode', [$promocodeId]);
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
