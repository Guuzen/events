<?php

namespace App\Queries\Tariff\FindTariffById;

use App\Infrastructure\Persistence\DBALTypes\JsonDocumentType;

final class TariffByIdType extends JsonDocumentType
{
    public function getName(): string
    {
        return $this->className();
    }

    protected function className(): string
    {
        return TariffById::class;
    }
}
