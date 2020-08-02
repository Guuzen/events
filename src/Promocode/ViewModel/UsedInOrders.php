<?php

namespace App\Promocode\ViewModel;

use App\Infrastructure\InlineNormalizer\InlineDenormalizable;
use App\Infrastructure\InlineNormalizer\InlineNormalizable;
use App\Order\ViewModel\OrderId;

/**
 * @InlineNormalizable()
 * @InlineDenormalizable()
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
