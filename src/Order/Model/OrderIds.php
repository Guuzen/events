<?php


namespace App\Order\Model;

use Traversable;

final class OrderIds implements \IteratorAggregate
{
    /**
     * @var OrderId[]
     */
    private $orderIds;

    public function __construct(array $orderIds)
    {
        $this->orderIds = $orderIds;
    }

    public function push(OrderId $orderId): void
    {
        $this->orderIds[] = $orderId;
    }

    public function contains(OrderId $orderId): bool
    {
        foreach ($this->orderIds as $id) {
            if ($id->equals($orderId)) {
                return true;
            }
        }

        return false;
    }

    public function containsAllOf(OrderIds $orderIds): bool
    {
        foreach ($orderIds as $orderId) {
            if ($this->contains($orderId)) {
                return false;
            }
        }

        return true;
    }

    public function getIterator(): Traversable
    {
        return yield from $this->orderIds;
    }
}
