<?php

namespace App\Queries\Order\FindOrdersInList;

use App\Infrastructure\Persistence\DoctrineTypesInitializer\JsonDocumentType;

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
