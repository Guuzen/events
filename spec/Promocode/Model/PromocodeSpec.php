<?php

namespace spec\App\Promocode\Model;

use App\Model\Event\EventId;
use App\Model\Order\OrderId;
use App\Model\Promocode\AllowedTariffs\SpecificAllowedTariffs;
use App\Model\Promocode\Discount\FixedDiscount;
use App\Model\Promocode\Exception\PromocodeExpired;
use App\Model\Promocode\Exception\PromocodeNotAllowedForTariff;
use App\Model\Promocode\Exception\PromocodeNotUsable;
use App\Model\Promocode\Exception\PromocodeNotUsedInOrder;
use App\Model\Promocode\Exception\PromocodeUseLimitExceeded;
use App\Model\Promocode\Promocode;
use App\Model\Promocode\PromocodeId;
use App\Model\Tariff\TariffId;
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
class PromocodeSpec extends ObjectBehavior
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
        $this->shouldHaveType(Promocode::class);
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
}
