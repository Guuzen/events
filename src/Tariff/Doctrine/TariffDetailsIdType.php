<?php

namespace App\Tariff\Doctrine;

use App\Infrastructure\Persistence\UuidType;
use App\Tariff\Model\TariffDetailsId;

final class TariffDetailsIdType extends UuidType
{
    public function getName(): string
    {
        return 'app_tariff_details_id';
    }

    protected function className(): string
    {
        return TariffDetailsId::class;
    }
}
