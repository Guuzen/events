<?php

namespace App\Queries\Order\FindOrdersInList;

use App\Common\JsonDocumentType;

final class OrderInListType extends JsonDocumentType
{
    public function getName(): string
    {
        return $this->className();
    }

    protected function className(): string
    {
        return OrderInList::class;
    }
}
