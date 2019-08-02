<?php

namespace spec\App\Promocode\Model;

use App\Event\Model\EventId;
use App\Order\Model\OrderId;
use App\Promocode\Model\Exception\PromocodeAlreadyUsedInOrder;
use App\Promocode\Model\Exception\PromocodeAndTariffRelatedToDifferentEvents;
use App\Promocode\Model\Exception\PromocodeNotAllowedForTariff;
use App\Promocode\Model\Exception\PromocodeNotUsable;
use App\Promocode\Model\Exception\PromocodeNotUsedInOrder;
use App\Promocode\Model\Exception\PromocodeUseLimitExceeded;
use App\Promocode\Model\Discount\FixedDiscount;
use App\Promocode\Model\PromocodeId;
use App\Promocode\Model\RegularPromocode;
use App\Promocode\Model\AllowedTariffs\SpecificAllowedTariffs;
use App\Tariff\Model\Exception\PromocodeExpired;
use App\Tariff\Model\Tariff;
use App\Tariff\Model\TicketTariffId;
use DateTimeImmutable;
use Money\Currency;
use Money\Money;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * вопросы:
 * 2. Как сильно нужно детализировать тест? Нужно ли сначала показывать, нерабочую ситуацию, а потом её исправлять?
 * 3. Что делать, если phpspec форсит создание интерфейса?
 *
 * TODO try to test interface
 *
 * TODO проверить иммутабельность всех VO
 * TODO сделать что-нибудь с _toString методами (коллекции?)
 * TODO дать переменным более говорящие имена ex. $expireTomorrow = new DateTime('tomorrow')
 */
class RegularPromocodeSpec extends ObjectBehavior
{
    public function let()
    {
        $eventId                  = new EventId();
        $limitedByOneUse          = 1;
        $expireTomorrow           = new DateTimeImmutable('tomorrow');
        $noSpecificAllowedTariffs = new SpecificAllowedTariffs([]);
        $zeroDiscount             = new FixedDiscount(new Money(0, new Currency('RUB')));
        $usable                   = true;
        $promocodeId              = new PromocodeId();
        $this->beConstructedWith(
            $promocodeId,
            $eventId,
            $zeroDiscount,
            $limitedByOneUse,
            $expireTomorrow,
            $noSpecificAllowedTariffs,
            $usable
        );
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType(RegularPromocode::class);
    }

    // use promocode
    public function it_should_be_used(Tariff $tariff)
    {
        $orderId = new OrderId();
        $now = new DateTimeImmutable('now');

        $tariff->beADoubleOf(Tariff::class);
        $tariff
            ->relatedToEvent(Argument::any())
            ->willReturn(true)
        ;
        $tariff
            ->allowedForUse(Argument::any())
            ->willReturn(true)
        ;

        $this
            ->shouldNotThrow()
            ->during('use', [$orderId, $tariff, $now])
        ;
    }

    public function it_should_not_be_used_if_not_usable(Tariff $tariff)
    {
        $eventId                  = new EventId();
        $orderId                = new OrderId();
        $zeroDiscount             = new FixedDiscount(new Money(0, new Currency('RUB')));
        $limitedByOneUse          = 1;
        $expireTomorrow           = new DateTimeImmutable('tomorrow');
        $noSpecificAllowedTariffs = new SpecificAllowedTariffs([new TicketTariffId()]);
        $now                      = new DateTimeImmutable('now');
        $promocodeId              = new PromocodeId();
        $notUsable                = false;
        $this->beConstructedWith(
            $promocodeId,
            $eventId,
            $zeroDiscount,
            $limitedByOneUse,
            $expireTomorrow,
            $noSpecificAllowedTariffs,
            $notUsable
        );

        $tariff->beADoubleOf(Tariff::class);
        $tariff
            ->relatedToEvent(Argument::any())
            ->willReturn(true)
        ;
        $tariff
            ->allowedForUse(Argument::any())
            ->willReturn(true)
        ;

        $this
            ->shouldThrow(PromocodeNotUsable::class)
            ->during('use', [$orderId, $tariff, $now])
        ;
    }

    public function it_should_not_be_used_twice_in_same_order(Tariff $tariff)
    {
        $orderId = new OrderId();
        $now = new DateTimeImmutable('now');

        $tariff->beADoubleOf(Tariff::class);
        $tariff
            ->relatedToEvent(Argument::any())
            ->willReturn(true)
        ;
        $tariff
            ->allowedForUse(Argument::any())
            ->willReturn(true)
        ;

        $this->use($orderId, $tariff, $now);
        $this
            ->shouldThrow(PromocodeAlreadyUsedInOrder::class)
            ->during('use', [$orderId, $tariff, $now])
        ;
    }

    public function it_should_not_be_usable_if_expired(Tariff $tariff)
    {
        $orderId = new OrderId();
        $backOfTomorrow = new DateTimeImmutable('tomorrow + 15 minutes');

        $tariff->beADoubleOf(Tariff::class);
        $tariff
            ->relatedToEvent(Argument::any())
            ->willReturn(true)
        ;
        $tariff
            ->allowedForUse(Argument::any())
            ->willReturn(true)
        ;

        $this
            ->shouldThrow(PromocodeExpired::class)
            ->during('use', [$orderId, $tariff, $backOfTomorrow])
        ;
    }

    public function it_should_not_be_used_if_use_limit_exceeded(Tariff $tariff)
    {
        $orderId = new OrderId();
        $now = new DateTimeImmutable('now');

        $tariff->beADoubleOf(Tariff::class);
        $tariff
            ->relatedToEvent(Argument::any())
            ->willReturn(true)
        ;
        $tariff
            ->allowedForUse(Argument::any())
            ->willReturn(true)
        ;

        $this->use(new OrderId(), $tariff, new DateTimeImmutable('now'));
        $this
            ->shouldThrow(PromocodeUseLimitExceeded::class)
            ->during('use', [$orderId, $tariff, $now])
        ;
    }

    public function it_should_not_be_related_to_different_event_with_tariff(Tariff $tariff)
    {
        $orderId = new OrderId();
        $now = new DateTimeImmutable('now');

        $tariff->beADoubleOf(Tariff::class);
        $tariff
            ->relatedToEvent(Argument::any())
            ->willReturn(false)
        ;
        $tariff
            ->allowedForUse(Argument::any())
            ->willReturn(true)
        ;

        $this
            ->shouldThrow(PromocodeAndTariffRelatedToDifferentEvents::class)
            ->during('use', [$orderId, $tariff, $now])
        ;
    }

    public function it_should_not_be_used_if_tariff_not_allowed(Tariff $tariff)
    {
        $orderId = new OrderId();
        $now = new DateTimeImmutable('now');

        $tariff->beADoubleOf(Tariff::class);
        $tariff
            ->relatedToEvent(Argument::any())
            ->willReturn(true)
        ;
        $tariff
            ->allowedForUse(Argument::any())
            ->willReturn(false)
        ;

        $this
            ->shouldThrow(PromocodeNotAllowedForTariff::class)
            ->during('use', [$orderId, $tariff, $now])
        ;
    }

//    public function it_should_not_be_used_without_calculated_sum(Tariff $tariff, User $user)
//    {
//        $eventId        = new EventId();
//        $orderId      = new InvoiceId();
//        $discount       = new FixedDiscount(new Money(0, new Currency('RUB')));
//        $useLimit       = 1;
//        $expireAt       = new DateTimeImmutable('tomorrow');
//        $allowedTariffs = [new TariffId()];
//        $asOf           = new DateTimeImmutable('now');
//        $useConstraints = new RegularPromocodeType($eventId, $useLimit, $expireAt, $allowedTariffs);
//        $this->beConstructedWith(new PromocodeId(), $discount, $useConstraints);
//
//        $user->beADoubleOf(User::class);
//
//        $tariff->beADoubleOf(Tariff::class);
//        $tariff
//            ->relatedToEvent($eventId)
//            ->willReturn(true)
//        ;
//        $tariff
//            ->allowedForUse($allowedTariffs)
//            ->willReturn(true)
//        ;
//        $tariff
//            ->calculateSum($this, $asOf)
//            ->willReturn(null)
//        ;
//
//        $this
//            ->shouldThrow(CantCalculateSum::class)
//            ->during('use', [$orderId, $tariff, $asOf])
//        ;
//    }

    // cancel promocode
    public function it_should_be_cancelled(Tariff $tariff)
    {
        $orderId = new OrderId();
        $now = new DateTimeImmutable('now');

        $tariff->beADoubleOf(Tariff::class);
        $tariff
            ->relatedToEvent(Argument::any())
            ->willReturn(true)
        ;
        $tariff
            ->allowedForUse(Argument::any())
            ->willReturn(true)
        ;

        $this->use($orderId, $tariff, $now);
        $this
            ->shouldNotThrow()
            ->during('cancel', [$orderId])
        ;
    }

    public function it_should_not_be_cancelled_if_not_used_in_order()
    {
        $orderId = new OrderId();

        $this
            ->shouldThrow(PromocodeNotUsedInOrder::class)
            ->during('cancel', [$orderId])
        ;
    }

    // changeUseLimit
//    public function it_should_change_use_limit()
//    {
//        $eventId        = new EventId();
//        $discount       = new FixedDiscount(new Money(0, new Currency('RUB')));
//        $useLimit       = 0;
//        $expireAt       = new DateTimeImmutable('tomorrow');
//        $allowedTariffs = [new TariffId()];
//        $promocodeId    = new PromocodeId();
//        $usable         = true;
//        $this->beConstructedWith($promocodeId, $eventId, $discount, $useLimit, $expireAt, $allowedTariffs, $usable);
//
//        $this
//            ->shouldNotThrow()
//            ->during('changeUseLimit', [1])
//        ;
//    }
//
//    public function it_should_not_decrease_use_limit_below_already_used(Tariff $tariff, Invoice $invoice)
//    {
//        $eventId        = new EventId();
//        $orderId      = new InvoiceId();
//        $discount       = new FixedDiscount(new Money(0, new Currency('RUB')));
//        $useLimit       = 3;
//        $expireAt       = new DateTimeImmutable('tomorrow');
//        $allowedTariffs = [new TariffId()];
//        $asOf           = new DateTimeImmutable('now');
//        $promocodeId    = new PromocodeId();
//        $usable         = true;
//        $this->beConstructedWith($promocodeId, $eventId, $discount, $useLimit, $expireAt, $allowedTariffs, $usable);
//
//        $invoice->beADoubleOf(Invoice::class);
//
//        $tariff->beADoubleOf(Tariff::class);
//        $tariff
//            ->relatedToEvent($eventId)
//            ->willReturn(true)
//        ;
//        $tariff
//            ->allowedForUse($allowedTariffs)
//            ->willReturn(true)
//        ;
//
//        $this->use($orderId, $tariff, $asOf);
//        $this
//            ->shouldThrow(PromocodeUseLimitCantDecreaseBelowAlreadyUsed::class)
//            ->during('changeUseLimit', [0])
//        ;
//    }
//
//    public function it_should_apply_discount()
//    {
//        $eventId        = new EventId();
//        $discount       = new FixedDiscount(new Money(1, new Currency('RUB')));
//        $useLimit       = 1;
//        $expireAt       = new DateTimeImmutable('tomorrow');
//        $allowedTariffs = [new TariffId()];
//        $price          = new Money(3, new Currency('RUB'));
//        $promocodeId    = new PromocodeId();
//        $usable         = true;
//        $this->beConstructedWith($promocodeId, $eventId, $discount, $useLimit, $expireAt, $allowedTariffs, $usable);
//
//        $this
//            ->applyDiscount($price)
//            ->shouldBeLike(new Money(2, new Currency('RUB')))
//        ;
//    }
}
