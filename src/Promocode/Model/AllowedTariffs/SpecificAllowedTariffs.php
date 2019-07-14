<?php

namespace App\Promocode\Model\AllowedTariffs;

use App\Order\Model\TariffId;

final class SpecificAllowedTariffs implements AllowedTariffs
{
    private $tariffIds;

    /**
     * @param TariffId[] $tariffIds
     */
    public function __construct(array $tariffIds)
    {
        $this->tariffIds = $tariffIds;
    }

    public function contains(TariffId $tariffId): bool
    {
        foreach ($this->tariffIds as $id) {
            if ($id->equals($tariffId)) {
                return true;
            }
        }

        return false;
    }
}
