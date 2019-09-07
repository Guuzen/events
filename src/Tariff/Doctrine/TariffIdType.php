<?php

namespace App\Tariff\Doctrine;

use App\Infrastructure\Persistence\UuidType;
use App\Tariff\Model\TariffId;

/**
 * @template-extends UuidType<TariffId>
 */
final class TariffIdType extends UuidType
{
    public function getName(): string
    {
        return 'app_tariff_id';
    }

    protected function className(): string
    {
        return TariffId::class;
    }
}
