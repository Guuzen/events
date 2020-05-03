<?php

namespace App\Product\Model;

use App\Infrastructure\Uuid;

/**
 * @template-extends Uuid<TicketId>
 * @psalm-immutable
 */
final class TicketId extends Uuid
{
}
