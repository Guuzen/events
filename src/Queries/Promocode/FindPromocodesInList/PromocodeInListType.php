<?php

namespace App\Queries\Promocode\FindPromocodesInList;

use App\Infrastructure\Persistence\DBALTypes\JsonDocumentType;

final class PromocodeInListType extends JsonDocumentType
{
    public function getName(): string
    {
        return $this->className();
    }

    protected function className(): string
    {
        return PromocodeInList::class;
    }
}
