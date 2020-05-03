<?php

namespace App\Product\Model;

use App\Infrastructure\Uuid;

/**
 * @template-extends Uuid<BroadcastLinkId>
 * @psalm-immutable
 */
final class BroadcastLinkId extends Uuid
{
}
