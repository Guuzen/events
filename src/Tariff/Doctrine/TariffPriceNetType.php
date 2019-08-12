<?php

namespace App\Tariff\Doctrine;

use App\Common\JsonDocumentType;
use App\Tariff\Model\TariffPriceNet;

final class TariffPriceNetType extends JsonDocumentType
{
    protected function className(): string
    {
        return TariffPriceNet::class;
    }

    public function getName(): string
    {
        return 'app_tariff_price_net';
    }
}
