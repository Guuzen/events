<?php

namespace App\Queries\Event\FindEventsInList;

use App\Infrastructure\Persistence\DBALTypes\JsonDocumentType;

final class EventInListType extends JsonDocumentType
{
    public function getName(): string
    {
        return $this->className();
    }

    protected function className(): string
    {
        return EventInList::class;
    }
}
