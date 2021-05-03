<?php

namespace App\Model\Event;

use App\Model\Order\Order;
use App\Model\Order\OrderId;
use App\Model\Ticket\Ticket;
use App\Model\Ticket\TicketId;
use App\Model\Promocode\AllowedTariffs\AllowedTariffs;
use App\Model\Promocode\AllowedTariffs\EventAllowedTariffs;
use App\Model\Promocode\Discount\Discount;
use App\Model\Promocode\Promocode;
use App\Model\Promocode\PromocodeId;
use App\Model\Tariff\ProductType;
use App\Model\Tariff\Tariff;
use App\Model\Tariff\TariffId;
use App\Model\Tariff\TariffPriceNet;
use App\Model\User\UserId;
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
     * @ORM\Column(type=EventId::class)
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

    public function makeOrder(
        OrderId $orderId,
        Tariff $tariff,
        UserId $userId,
        Money $price,
        DateTimeImmutable $asOf
    ): Order
    {
        return $tariff->makeOrder($orderId, $this->id, $userId, $price, $asOf);
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
