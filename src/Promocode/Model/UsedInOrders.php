<?php

namespace App\Promocode\Model;

use App\Order\Model\OrderId;

final class UsedInOrders
{
    private $orderIds;

    public function __construct(array $orderIds)
    {
        $this->orderIds = $orderIds;
    }

    public function add(OrderId $orderId): self
    {
        $orderIds = $this->orderIds;
        $orderIds[] = $orderId;

        return new self($orderIds);
    }

    public function remove(OrderId $orderId): void
    {
        $this->orderIds = array_diff($this->orderIds, [$orderId]);
    }

    public function count(): int
    {
        return count($this->orderIds);
    }

    public function has(OrderId $orderId): bool
    {
        return in_array($orderId, $this->orderIds, true);
    }
}
