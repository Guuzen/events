<?php

namespace spec\App\Promocode\Model;

use App\Event\Model\EventId;
use App\Order\Model\OrderId;
use App\Promocode\Model\AllowedTariffs\SpecificAllowedTariffs;
use App\Promocode\Model\Discount\FixedDiscount;
use App\Promocode\Model\Exception\PromocodeAndTariffRelatedToDifferentEvents;
use App\Promocode\Model\Exception\PromocodeNotAllowedForTariff;
use App\Promocode\Model\Exception\PromocodeNotUsable;
use App\Promocode\Model\Exception\PromocodeNotUsedInOrder;
use App\Promocode\Model\Exception\PromocodeUseLimitExceeded;
use App\Promocode\Model\PromocodeId;
use App\Promocode\Model\RegularPromocode;
use App\Tariff\Model\Exception\PromocodeExpired;
use App\Tariff\Model\Tariff;
use App\Tariff\Model\TariffId;
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
        $eventId                  = EventId::new();
        $code                     = 'FOO';
        $limitedByOneUse          = 1;
        $expireTomorrow           = new DateTimeImmutable('tomorrow');
        $noSpecificAllowedTariffs = new SpecificAllowedTariffs([]);
        $zeroDiscount             = new FixedDiscount(new Money(0, new Currency('RUB')));
        $usable                   = true;
        $promocodeId              = PromocodeId::new();
        $this->beConstructedWith(
            $promocodeId,
            $eventId,
            $code,
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

    public function it_should_be_possible_to_cancel_when_used(Tariff $tariff)
    {
        $orderId = OrderId::new();
        $now     = new DateTimeImmutable('now');

        $tariff->beADoubleOf(Tariff::class);
        $tariff
            ->relatedToEvent(Argument::any())
            ->willReturn(true);
        $tariff
            ->allowedForUse(Argument::any())
            ->willReturn(true);

        $this->use($orderId, $tariff, $now);
        $this
            ->shouldNotThrow()
            ->during('cancel', [$orderId]);
    }

    public function it_should_not_be_possible_to_cancel_when_not_used()
    {
        $orderId = OrderId::new();

        $this
            ->shouldThrow(PromocodeNotUsedInOrder::class)
            ->during('cancel', [$orderId]);
    }

    public function it_should_be_possible_to_use_again_when_cancelled(Tariff $tariff)
    {
        $orderId = OrderId::new();
        $now     = new DateTimeImmutable('now');

        $tariff->beADoubleOf(Tariff::class);
        $tariff
            ->relatedToEvent(Argument::any())
            ->willReturn(true);
        $tariff
            ->allowedForUse(Argument::any())
            ->willReturn(true);

        $this->use($orderId, $tariff, $now);
        $this->cancel($orderId);
        $this
            ->shouldNotThrow()
            ->during('use', [$orderId, $tariff, $now]);
    }

    // use promocode

    public function it_should_be_possible_to_use(Tariff $tariff)
    {
        $orderId = OrderId::new();
        $now     = new DateTimeImmutable('now');

        $tariff->beADoubleOf(Tariff::class);
        $tariff
            ->relatedToEvent(Argument::any())
            ->willReturn(true);
        $tariff
            ->allowedForUse(Argument::any())
            ->willReturn(true);

        $this
            ->shouldNotThrow()
            ->during('use', [$orderId, $tariff, $now]);
    }

    public function it_should_not_be_possible_to_use_when_not_usable(Tariff $tariff)
    {
        $orderId = OrderId::new();
        $now     = new DateTimeImmutable('now');

        $tariff->beADoubleOf(Tariff::class);
        $tariff
            ->relatedToEvent(Argument::any())
            ->willReturn(true);
        $tariff
            ->allowedForUse(Argument::any())
            ->willReturn(true);

        $this->makeNotUsable();
        $this
            ->shouldThrow(PromocodeNotUsable::class)
            ->during('use', [$orderId, $tariff, $now]);
    }

    public function it_should_not_be_possible_to_use_when_expired(Tariff $tariff)
    {
        $orderId        = OrderId::new();
        $backOfTomorrow = new DateTimeImmutable('tomorrow + 15 minutes');

        $tariff->beADoubleOf(Tariff::class);
        $tariff
            ->relatedToEvent(Argument::any())
            ->willReturn(true);
        $tariff
            ->allowedForUse(Argument::any())
            ->willReturn(true);

        $this
            ->shouldThrow(PromocodeExpired::class)
            ->during('use', [$orderId, $tariff, $backOfTomorrow]);
    }

    public function it_should_not_be_possible_to_use_when_use_limit_exceeded(Tariff $tariff)
    {
        $orderId = OrderId::new();
        $now     = new DateTimeImmutable('now');

        $tariff->beADoubleOf(Tariff::class);
        $tariff
            ->relatedToEvent(Argument::any())
            ->willReturn(true);
        $tariff
            ->allowedForUse(Argument::any())
            ->willReturn(true);

        $this->use(OrderId::new(), $tariff, new DateTimeImmutable('now'));
        $this
            ->shouldThrow(PromocodeUseLimitExceeded::class)
            ->during('use', [$orderId, $tariff, $now]);
    }

    public function it_should_not_be_possible_to_use_when_tariff_is_related_to_different_event(Tariff $tariff)
    {
        $orderId = OrderId::new();
        $now     = new DateTimeImmutable('now');

        $tariff->beADoubleOf(Tariff::class);
        $tariff
            ->relatedToEvent(Argument::any())
            ->willReturn(false);
        $tariff
            ->allowedForUse(Argument::any())
            ->willReturn(true);

        $this
            ->shouldThrow(PromocodeAndTariffRelatedToDifferentEvents::class)
            ->during('use', [$orderId, $tariff, $now]);
    }

    public function it_should_not_be_possible_to_use_when_tariff_not_allowed(Tariff $tariff)
    {
        $orderId = OrderId::new();
        $now     = new DateTimeImmutable('now');

        $tariff->beADoubleOf(Tariff::class);
        $tariff
            ->relatedToEvent(Argument::any())
            ->willReturn(true);
        $tariff
            ->allowedForUse(Argument::any())
            ->willReturn(false);

        $this
            ->shouldThrow(PromocodeNotAllowedForTariff::class)
            ->during('use', [$orderId, $tariff, $now]);
    }

    public function it_should_be_possible_to_apply_discount()
    {
        $eventId        = EventId::new();
        $discount       = new FixedDiscount(new Money(1, new Currency('RUB')));
        $code           = 'FOO';
        $useLimit       = 1;
        $expireAt       = new DateTimeImmutable('tomorrow');
        $allowedTariffs = new SpecificAllowedTariffs([TariffId::new()]);
        $price          = new Money(3, new Currency('RUB'));
        $promocodeId    = PromocodeId::new();
        $usable         = true;
        $this->beConstructedWith($promocodeId, $eventId, $code, $discount, $useLimit, $expireAt, $allowedTariffs, $usable);

        $this
            ->apply($price)
            ->shouldBeLike(new Money(2, new Currency('RUB')));
    }
}
