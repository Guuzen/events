<?php
declare(strict_types=1);

namespace App\Event\Model;

use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\Product;
use App\Promocode\Model\AllowedTariffs\EventAllowedTariffs;
use App\Promocode\Model\Discount\Discount;
use App\Promocode\Model\Exception\CantCalculateSum;
use App\Promocode\Model\Promocode;
use App\Promocode\Model\PromocodeId;
use App\Promocode\Model\RegularPromocode;
use App\Promocode\Model\AllowedTariffs\SpecificAllowedTariffs;
use App\Tariff\Model\Tariff;
use App\Tariff\Model\TariffId;
use App\Tariff\Model\TariffPriceNet;
use App\Tariff\Model\Tariffs;
use App\User\Model\User;
use DateTimeImmutable;

final class Event
{
    private $id;

    /** @var ActiveTariff[] */
    private $activeTariffs;

    public function __construct(EventId $id, array $activeTariffs = [])
    {
        $this->id            = $id;
        $this->activeTariffs = $activeTariffs;
    }

    public function activateTariff(Tariff $tariff, ActiveTariffType $tariffType): void
    {
        // может быть только по 1 тарифу каждого типа
        $activeTariff = $tariff->createActiveTariff($tariffType);
        $activeTariffIndex = $this->findActiveTariffIndexByType($tariffType);
        if (null === $activeTariffIndex) {
            $this->activeTariffs[] = $activeTariff;
        } else {
            $this->activeTariffs[$activeTariffIndex] = $activeTariff;
        }
    }

    public function createTariff(TariffId $tariffId, TariffPriceNet $priceNet): Tariff
    {
        return new Tariff($tariffId, $this->id, $priceNet);
    }

    public function makeOrder(
        OrderId $orderId,
        Promocode $promocode,
        Tariffs $tariffs,
        Product $product,
        User $user,
        DateTimeImmutable $createdAt,
        ActiveTariffType $tariffType
    ): Order
    {

        // TODO соответствие product && tariff
        foreach ($this->activeTariffs as $activeTariff) {
            if ($activeTariff->sameTypeAs($tariffType)) {
                $tariff = $activeTariff->getTariff($tariffs);

                /**
                 * Считать сумму можно и в промокоде, неясно как выбрать.
                 */
                $sum = $tariff->calculateSum($promocode, $createdAt);
                if (null === $sum) {
                    throw new CantCalculateSum();
                }

                return $promocode->makeOrder($orderId, $this->id, $tariff, $user, $product, $sum, $createdAt);

            }
        }

        throw new UnknownActiveTariffType();
    }

    public function createEventPromocode(
        PromocodeId $promocodeId,
        Discount $discount,
        int $useLimit,
        DateTimeImmutable $expireAt
    ): RegularPromocode
    {
        return new RegularPromocode(
            $promocodeId,
            $this->id,
            $discount,
            $useLimit,
            $expireAt,
            new EventAllowedTariffs()
        );
    }

    public function createRegularPromocode(
        PromocodeId $promocodeId,
        Discount $discount,
        int $useLimit,
        DateTimeImmutable $expireAt,
        SpecificAllowedTariffs $specificTariffs
    ): RegularPromocode
    {
        return new RegularPromocode($promocodeId, $this->id, $discount, $useLimit, $expireAt, $specificTariffs);
    }

    private function findActiveTariffIndexByType(ActiveTariffType $activeTariffType): ?int
    {
        foreach ($this->activeTariffs as $index => $activeTariff) {
            if ($activeTariff->sameTypeAs($activeTariffType)) {
                return $index;
            }
        }

        return null;
    }
}
