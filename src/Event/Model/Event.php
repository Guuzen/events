<?php

namespace App\Event\Model;

use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\Product;
use App\Product\Model\ProductType;
use App\Product\Model\Ticket;
use App\Product\Model\TicketId;
use App\Promocode\Model\AllowedTariffs\EventAllowedTariffs;
use App\Promocode\Model\AllowedTariffs\SpecificAllowedTariffs;
use App\Promocode\Model\Discount\Discount;
use App\Promocode\Model\PromocodeId;
use App\Promocode\Model\RegularPromocode;
use App\Tariff\Model\Tariff;
use App\Tariff\Model\TariffPriceNet;
use App\Tariff\Model\TariffId;
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

    // TODO number should be limited to 8 digits!
    public function createTicket(TicketId $ticketId, string $number): Ticket
    {
        return new Ticket($ticketId, $this->id, $number);
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

    public function createTariff(
        TariffId $tariffId,
        TariffPriceNet $tariffPriceNet,
        ProductType $productType
    ): Tariff {
        return new Tariff($tariffId, $this->id, $tariffPriceNet, $productType);
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
