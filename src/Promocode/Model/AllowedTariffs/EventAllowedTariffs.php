<?php
declare(strict_types=1);

namespace App\Promocode\Model\AllowedTariffs;

use App\Order\Model\TariffId;

final class EventAllowedTariffs implements AllowedTariffs
{
    public function contains(TariffId $tariffId): bool
    {
        return true;
    }
}
