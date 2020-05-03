<?php

namespace App\Order\Model;

use App\Infrastructure\Uuid;

/**
 * @template-extends Uuid<OrderId>
 * @psalm-immutable
 */
final class OrderId extends Uuid
{
}
