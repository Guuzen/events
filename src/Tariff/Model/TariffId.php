<?php

namespace App\Tariff\Model;

use App\Infrastructure\Uuid;

/**
 * @template-extends Uuid<TariffId>
 * @psalm-immutable
 */
final class TariffId extends Uuid
{
}
