<?php

namespace App\Promocode\Model;

use App\Infrastructure\Persistence\DBALTypes\JsonDocumentType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use App\Order\Model\OrderId;

/**
 * @DBALType(typeClass=JsonDocumentType::class)
 *
 * @psalm-immutable
 */
final class UsedInOrders
{
    /**
     * @var OrderId[]
     */
    private $orderIds;

    /**
     * @param OrderId[] $orderIds
     */
    public function __construct(array $orderIds)
    {
        $this->orderIds = $orderIds;
    }

    public function add(OrderId $orderId): self
    {
        $orderIds   = $this->orderIds;
        $orderIds[] = $orderId;

        return new self($orderIds);
    }

    public function remove(OrderId $orderId): self
    {
        return new self(\array_diff($this->orderIds, [$orderId]));
    }

    public function count(): int
    {
        return \count($this->orderIds);
    }

    public function has(OrderId $orderId): bool
    {
        return \in_array($orderId, $this->orderIds, true);
    }
}
