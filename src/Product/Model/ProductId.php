<?php

namespace App\Product\Model;

use App\Infrastructure\Uuid;

/**
 * @template-extends Uuid<ProductId>
 * @psalm-immutable
 */
final class ProductId extends Uuid
{
}
