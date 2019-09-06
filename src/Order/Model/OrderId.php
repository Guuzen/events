<?php

namespace App\Order\Model;

use App\Infrastructure\Uuid;

/**
 * @template-extends Uuid<OrderId>
 */
final class OrderId extends Uuid
{
}
