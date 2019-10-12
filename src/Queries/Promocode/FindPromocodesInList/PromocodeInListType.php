<?php

namespace App\Queries\Promocode\FindPromocodesInList;

use App\Common\JsonDocumentType;

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
