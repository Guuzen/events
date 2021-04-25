<?php

namespace App\Model\Promocode\AllowedTariffs;

use App\Model\Tariff\TariffId;

/**
 * @psalm-immutable
 */
final class SpecificAllowedTariffs implements AllowedTariffs
{
    /**
     * @var TariffId[]
     */
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
