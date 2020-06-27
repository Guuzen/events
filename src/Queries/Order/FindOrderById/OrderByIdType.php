<?php

namespace App\Queries\Order\FindOrderById;

use App\Infrastructure\Persistence\DBALTypes\JsonDocumentType;

final class OrderByIdType extends JsonDocumentType
{
    public function getName(): string
    {
        return $this->className();
    }

    protected function className(): string
    {
        return OrderById::class;
    }
}
