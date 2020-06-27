<?php

namespace App\Event\Model;

use App\Common\Error;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Product\Model\ProductType;
use App\Product\Model\Ticket;
use App\Product\Model\TicketId;
use App\Promocode\Model\AllowedTariffs\AllowedTariffs;
use App\Promocode\Model\AllowedTariffs\EventAllowedTariffs;
use App\Promocode\Model\Discount\Discount;
use App\Promocode\Model\PromocodeId;
use App\Promocode\Model\Promocode;
use App\Tariff\Model\Tariff;
use App\Tariff\Model\TariffId;
use App\Tariff\Model\TariffPriceNet;
use App\User\Model\UserId;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Money\Money;
use App\Infrastructure\Persistence\UuidType;

/**
 * @ORM\Entity
 */
final class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type=EventId::class, options={"typeClass": UuidType::class})
     */
    private $id;

    public function __construct(EventId $id)
    {
        $this->id = $id;
    }

    public function createTicket(TicketId $ticketId, OrderId $orderId, string $number, DateTimeImmutable $asOf): Ticket
    {
        return new Ticket($ticketId, $this->id, $orderId, $number, $asOf);
    }

    /**
     * @return Order|Error
     */
    public function makeOrder(
        OrderId $orderId,
        Tariff $tariff,
        UserId $userId,
        Money $sum,
        DateTimeImmutable $asOf
    )
    {
        return $tariff->makeOrder($orderId, $this->id, $userId, $sum, $asOf);
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
    ): Promocode
    {
        return new Promocode(
            $promocodeId,
            $this->id,
            $code,
            $discount,
            $useLimit,
            $expireAt,
            new EventAllowedTariffs()
        );
    }

    public function createTariffPromocode(
        PromocodeId $promocodeId,
        string $code,
        Discount $discount,
        int $useLimit,
        DateTimeImmutable $expireAt,
        AllowedTariffs $allowedTariffs
    ): Promocode
    {
        return new Promocode(
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
