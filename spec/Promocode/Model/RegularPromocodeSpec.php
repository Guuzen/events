<?php

namespace spec\App\Promocode\Model;

use App\Event\Model\EventId;
use App\Order\Model\OrderId;
use App\Promocode\Model\AllowedTariffs\SpecificAllowedTariffs;
use App\Promocode\Model\Discount\FixedDiscount;
use App\Promocode\Model\Exception\PromocodeNotAllowedForTariff;
use App\Promocode\Model\Exception\PromocodeNotUsable;
use App\Promocode\Model\Exception\PromocodeNotUsedInOrder;
use App\Promocode\Model\Exception\PromocodeUseLimitExceeded;
use App\Promocode\Model\PromocodeId;
use App\Promocode\Model\RegularPromocode;
use App\Tariff\Model\Exception\PromocodeExpired;
use App\Tariff\Model\TariffId;
use DateTimeImmutable;
use Money\Currency;
use Money\Money;
use PhpSpec\ObjectBehavior;

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
        $eventId                = EventId::new();
        $code                   = 'FOO';
        $limitedByOneUse        = 1;
        $expireTomorrow         = new DateTimeImmutable('tomorrow');
        $specificAllowedTariffs = new SpecificAllowedTariffs([new TariffId('d9d9db49-42c3-41ac-968a-70372f2b17a8')]);
        $zeroDiscount           = new FixedDiscount(new Money(0, new Currency('RUB')));
        $usable                 = true;
        $promocodeId            = PromocodeId::new();
        $this->beConstructedWith(
            $promocodeId,
            $eventId,
            $code,
            $zeroDiscount,
            $limitedByOneUse,
            $expireTomorrow,
            $specificAllowedTariffs,
            $usable
        );
    }

    public function it_should_be_initializable()
    {
        $this->shouldHaveType(RegularPromocode::class);
    }

    public function it_should_be_possible_to_cancel_when_used()
    {
        $orderId  = OrderId::new();
        $now      = new DateTimeImmutable('now');
        $tariffId = new TariffId('d9d9db49-42c3-41ac-968a-70372f2b17a8');

        $this->use($orderId, $tariffId, $now);
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

    public function it_should_be_possible_to_use_again_when_cancelled()
    {
        $orderId = OrderId::new();
        $now     = new DateTimeImmutable('now');

        $tariffId = new TariffId('d9d9db49-42c3-41ac-968a-70372f2b17a8');

        $this->use($orderId, $tariffId, $now);
        $this->cancel($orderId);
        $this
            ->shouldNotThrow()
            ->during('use', [$orderId, $tariffId, $now]);
    }

    // use promocode

    public function it_should_be_possible_to_use()
    {
        $orderId  = OrderId::new();
        $now      = new DateTimeImmutable('now');
        $tariffId = new TariffId('d9d9db49-42c3-41ac-968a-70372f2b17a8');

        $this
            ->shouldNotThrow()
            ->during('use', [$orderId, $tariffId, $now]);
    }

    public function it_should_not_be_possible_to_use_when_not_usable()
    {
        $orderId  = OrderId::new();
        $now      = new DateTimeImmutable('now');
        $tariffId = new TariffId('d9d9db49-42c3-41ac-968a-70372f2b17a8');

        $this->makeNotUsable();
        $this
            ->shouldThrow(PromocodeNotUsable::class)
            ->during('use', [$orderId, $tariffId, $now]);
    }

    public function it_should_not_be_possible_to_use_when_expired()
    {
        $orderId        = OrderId::new();
        $backOfTomorrow = new DateTimeImmutable('tomorrow + 15 minutes');
        $tariffId       = new TariffId('d9d9db49-42c3-41ac-968a-70372f2b17a8');

        $this
            ->shouldThrow(PromocodeExpired::class)
            ->during('use', [$orderId, $tariffId, $backOfTomorrow]);
    }

    public function it_should_not_be_possible_to_use_when_use_limit_exceeded()
    {
        $orderId  = OrderId::new();
        $now      = new DateTimeImmutable('now');
        $tariffId = new TariffId('d9d9db49-42c3-41ac-968a-70372f2b17a8');

        $this->use(OrderId::new(), $tariffId, new DateTimeImmutable('now'));
        $this
            ->shouldThrow(PromocodeUseLimitExceeded::class)
            ->during('use', [$orderId, $tariffId, $now]);
    }

    public function it_should_not_be_possible_to_use_when_tariff_not_allowed()
    {
        $orderId            = OrderId::new();
        $now                = new DateTimeImmutable('now');
        $notAllowedTariffId = new TariffId('fbc72e11-fb51-4f16-826e-1287c4a57523');

        $this
            ->shouldThrow(PromocodeNotAllowedForTariff::class)
            ->during('use', [$orderId, $notAllowedTariffId, $now]);
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
