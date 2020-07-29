<?php

namespace App\Promocode\ViewModel;

use App\Infrastructure\InlineNormalizer\InlineNormalizable;
use App\Order\ViewModel\OrderId;

/**
 * @InlineNormalizable()
 *
 * @psalm-immutable
 */
final class UsedInOrders
{
    /**
     * @var OrderId[]
     */
    private $orderIds;
}
