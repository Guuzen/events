<?php

namespace App\Model\Promocode\AllowedTariffs;

use App\Model\Tariff\TariffId;

final class EventAllowedTariffs implements AllowedTariffs
{
    public function contains(TariffId $tariffId): bool
    {
        return true;
    }
}
