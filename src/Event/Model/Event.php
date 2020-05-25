<?php

namespace App\Event\Model;

use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\Error\OrderAndProductMustBeRelatedToSameEvent;
use App\Product\Model\Error\OrderAndProductMustBeRelatedToSameTariff;
use App\Product\Model\Product;
use App\Product\Model\ProductType;
use App\Product\Model\Ticket;
use App\Product\Model\TicketId;
use App\Promocode\Model\AllowedTariffs\AllowedTariffs;
use App\Promocode\Model\AllowedTariffs\EventAllowedTariffs;
use App\Promocode\Model\Discount\Discount;
use App\Promocode\Model\PromocodeId;
use App\Promocode\Model\RegularPromocode;
use App\Tariff\Model\Error\TariffAndOrderMustBeRelatedToSameEvent;
use App\Tariff\Model\Tariff;
use App\Tariff\Model\TariffId;
use App\Tariff\Model\TariffPriceNet;
use App\User\Model\UserId;
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

    public function createTicket(TicketId $ticketId, string $number): Ticket
    {
        return new Ticket($ticketId, $this->id, $number);
    }

    /**
     * @return Order|TariffAndOrderMustBeRelatedToSameEvent|OrderAndProductMustBeRelatedToSameEvent|OrderAndProductMustBeRelatedToSameTariff
     */
    public function makeOrder(
        OrderId $orderId,
        Product $product,
        Tariff $tariff,
        UserId $userId,
        Money $sum,
        DateTimeImmutable $asOf
    )
    {
        return $tariff->makeOrder($orderId, $this->id, $product, $userId, $sum, $asOf);
    }

    public function createTariff(TariffId $tariffId, TariffPriceNet $tariffPriceNet, ProductType $productType): Tariff
    {
        return new Tariff($tariffId, $this->id, $tariffPriceNet, $productType);
    }

    public function createEventPromocode(
        PromocodeId $promocodeId,
        string $code,
        Discount $discount,
        int $useLimit,
        DateTimeImmutable $expireAt
    ): RegularPromocode
    {
        return new RegularPromocode(
            $promocodeId,
            $this->id,
            $code,
            $discount,
            $useLimit,
            $expireAt,
            new EventAllowedTariffs()
        );
    }

    public function createFixedPromocode(
        PromocodeId $promocodeId,
        string $code,
        Discount $discount,
        int $useLimit,
        DateTimeImmutable $expireAt,
        AllowedTariffs $allowedTariffs
    ): RegularPromocode
    {
        return new RegularPromocode(
            $promocodeId,
            $this->id,
            $code,
            $discount,
            $useLimit,
            $expireAt,
            $allowedTariffs
        );
    }
}
