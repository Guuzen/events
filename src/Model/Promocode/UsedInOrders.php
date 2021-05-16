<?php

namespace App\Model\Promocode;

use App\Infrastructure\InlineNormalizer\Inline;
use App\Infrastructure\Persistence\DBALTypes\JsonDocumentType;
use App\Infrastructure\Persistence\DBALTypesInitializer\CustomTypeAnnotation as DBALType;
use App\Model\TicketOrder\TicketOrderId;

/**
 * @DBALType(typeClass=JsonDocumentType::class)
 *
 * @Inline
 *
 * @psalm-immutable
 */
final class UsedInOrders
{
    /**
     * @var TicketOrderId[]
     */
    private $orderIds;

    /**
     * @param TicketOrderId[] $orderIds
     */
    public function __construct(array $orderIds)
    {
        $this->orderIds = $orderIds;
    }

    public function add(TicketOrderId $orderId): self
    {
        $orderIds   = $this->orderIds;
        $orderIds[] = $orderId;

        return new self($orderIds);
    }

    public function count(): int
    {
        return \count($this->orderIds);
    }
}
