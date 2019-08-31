<?php

namespace App\Queries\Event\FindEventsInList;

use App\Common\JsonDocumentType;

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
