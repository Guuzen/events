<?php
declare(strict_types=1);

namespace App\Promocode\Model\AllowedTariffs;

use App\Order\Model\TariffId;

interface AllowedTariffs
{
    public function contains(TariffId $tariffId): bool;
}
