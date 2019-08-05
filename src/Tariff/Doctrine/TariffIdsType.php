<?php


namespace App\Tariff\Doctrine;

use App\Common\JsonDocumentType;
use App\Tariff\Model\TariffIds;

final class TariffIdsType extends JsonDocumentType
{
    private const TYPE = 'app_tariff_ids';

    protected function className(): string
    {
        return TariffIds::class;
    }

    public function getName(): string
    {
        return self::TYPE;
    }
}
