<?php

namespace App\Queries\Tariff\FindTariffsInList;

use App\Common\JsonDocumentType;

final class TariffInListType extends JsonDocumentType
{
    public function getName(): string
    {
        return $this->className();
    }

    protected function className(): string
    {
        return TariffInList::class;
    }
}
