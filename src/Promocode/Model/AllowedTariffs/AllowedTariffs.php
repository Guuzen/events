<?php

namespace App\Promocode\Model\AllowedTariffs;

use App\Tariff\Model\TariffId;

interface AllowedTariffs
{
    public function contains(TariffId $tariffId): bool;
}
