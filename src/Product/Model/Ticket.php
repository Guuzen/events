<?php
declare(strict_types=1);

namespace App\Product\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Order\Model\ProductId;
use App\Promocode\Model\PromocodeId;
use App\Tariff\Model\TariffId;
use App\User\Model\User;
use DateTimeImmutable;
use Money\Money;

final class Ticket implements Product
{
    private $id;

    private $number;

    private $reserved;

    public function __construct(TicketId $id, int $number, bool $reserved = false)
    {
        $this->id     = $id;
        $this->number = $number;
        $this->reserved = $reserved;
    }

    public function reserve(): void
    {
        $this->reserved = true;
    }

    public function cancelReserve(): void
    {
        $this->reserved = false;
    }

    public function makeOrder(
        OrderId $orderId,
        EventId $eventId,
        TariffId $tariffId,
        ?PromocodeId $promocodeId,
        User $user,
        Money $sum,
        DateTimeImmutable $makedAt
    ): Order
    {
        $productId = new ProductId((string)$this->id);

        return $user->makeOrder($orderId, $eventId, $tariffId, $promocodeId, $productId, $sum, $makedAt);
    }
}
