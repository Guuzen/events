<?php

namespace App\Tariff\Model;

use App\Infrastructure\Uuid;

/**
 * @template-extends Uuid<TariffDetailsId>
 * @psalm-immutable
 */
final class TariffDetailsId extends Uuid
{
}
