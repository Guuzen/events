<?php

namespace App\Queries\Tariff\FindTariffsInList;

use App\Infrastructure\Persistence\DoctrineTypesInitializer\JsonDocumentType;

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
