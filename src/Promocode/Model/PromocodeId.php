<?php

namespace App\Promocode\Model;

use App\Infrastructure\Uuid;

/**
 * @template-extends Uuid<PromocodeId>
 * @psalm-immutable
 */
final class PromocodeId extends Uuid
{
}
