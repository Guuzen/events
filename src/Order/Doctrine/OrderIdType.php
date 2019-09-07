<?php

namespace App\Order\Doctrine;

use App\Infrastructure\Persistence\UuidType;
use App\Order\Model\OrderId;

/**
 * @template-extends UuidType<OrderId>
 */
final class OrderIdType extends UuidType
{
    public function getName(): string
    {
        return 'app_order_id';
    }

    protected function className(): string
    {
        return OrderId::class;
    }
}
