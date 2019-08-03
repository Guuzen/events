<?php

namespace App\Event\Model;

use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\Product;
use App\Product\Model\ProductType;
use App\Promocode\Model\AllowedTariffs\EventAllowedTariffs;
use App\Promocode\Model\AllowedTariffs\SpecificAllowedTariffs;
use App\Promocode\Model\Discount\Discount;
use App\Promocode\Model\PromocodeId;
use App\Promocode\Model\RegularPromocode;
use App\Tariff\Model\Tariff;
use App\Tariff\Model\TariffPriceNet;
use App\Tariff\Model\TicketTariffId;
use App\User\Model\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;

/**
 * @ORM\Entity
 */
final class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="app_event_id")
     */
    private $id;

    public function __construct(EventId $id)
    {
        $this->id = $id;
    }

    public function makeOrder(
        OrderId $orderId,
        Product $product,
        Tariff $tariff,
        Money $sum,
        User $user,
        DateTimeImmutable $asOf
    ): Order {
        return $product->makeOrder($orderId, $this->id, $tariff, $sum, $user, $asOf);
    }

    public function createTicketTariff(
        TicketTariffId $ticketTariffId,
        TariffPriceNet $tariffPriceNet,
        ProductType $productType
    ): Tariff {
        return new Tariff($ticketTariffId, $this->id, $tariffPriceNet, $productType);
    }

    public function createEventPromocode(
        PromocodeId $promocodeId,
        Discount $discount,
        int $useLimit,
        DateTimeImmutable $expireAt
    ): RegularPromocode {
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
        DateTimeImmutable $expireAt
    ): RegularPromocode {
        return new RegularPromocode(
            $promocodeId,
            $this->id,
            $discount,
            $useLimit,
            $expireAt,
            new SpecificAllowedTariffs()
        );
    }
}
