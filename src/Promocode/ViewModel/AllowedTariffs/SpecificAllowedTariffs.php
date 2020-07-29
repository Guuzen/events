<?php

namespace App\Promocode\ViewModel\AllowedTariffs;

use App\Tariff\ViewModel\TariffId;

/**
 * @psalm-immutable
 */
final class SpecificAllowedTariffs implements AllowedTariffs
{
    /**
     * @var TariffId[]
     */
    private $tariffIds;
}
