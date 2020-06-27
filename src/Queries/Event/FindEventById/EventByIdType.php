<?php

namespace App\Queries\Event\FindEventById;

use App\Infrastructure\Persistence\DBALTypes\JsonDocumentType;

final class EventByIdType extends JsonDocumentType
{
    public function getName(): string
    {
        return $this->className();
    }

    protected function className(): string
    {
        return EventById::class;
    }
}
