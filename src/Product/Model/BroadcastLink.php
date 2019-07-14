<?php

declare(strict_types=1);

namespace App\Product\Model;

use App\Event\Model\EventId;
use App\Order\Model\Order;
use App\Order\Model\OrderId;
use App\Order\Model\ProductId;
use App\Product\Model\Exception\OrderProductMustBeRelatedToEvent;
use App\Promocode\Model\Promocode;
use App\Tariff\Model\Tariff;
use App\User\Model\User;
use DateTimeImmutable;

final class BroadcastLink implements Product
{
    private $id;

    private $eventId;

    private $link;

    private $reserved;

    public function __construct(BroadcastLinkId $id, EventId $eventId, string $link, bool $reserved = false)
    {
        $this->id       = $id;
        $this->eventId  = $eventId;
        $this->link     = $link;
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
        Tariff $tariff,
        Promocode $promocode,
        User $user,
        DateTimeImmutable $asOf
    ): Order {
        if (!$this->eventId->equals($eventId)) {
            throw new OrderProductMustBeRelatedToEvent();
        }
        $productId = ProductId::fromString((string) $this->id);

        return $tariff->makeOrder($orderId, $eventId, $productId, $promocode, $user, $asOf);
    }
}
