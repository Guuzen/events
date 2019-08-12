<?php

namespace App\Promocode\Doctrine;

use App\Common\JsonDocumentType;
use App\Promocode\Model\AllowedTariffs\AllowedTariffs;

final class PromocodeAllowedTariffsType extends JsonDocumentType
{
    protected function className(): string
    {
        return AllowedTariffs::class;
    }

    public function getName(): string
    {
        return 'app_promocode_allowed_tariffs';
    }
}
