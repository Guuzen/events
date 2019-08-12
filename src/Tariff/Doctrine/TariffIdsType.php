<?php

namespace App\Tariff\Doctrine;

use App\Common\JsonDocumentType;
use App\Tariff\Model\TariffIds;

final class TariffIdsType extends JsonDocumentType
{
    protected function className(): string
    {
        return TariffIds::class;
    }

    public function getName(): string
    {
        return 'app_tariff_ids';
    }
}
