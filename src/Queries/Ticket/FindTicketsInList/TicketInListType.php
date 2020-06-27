<?php

namespace App\Queries\Ticket\FindTicketsInList;

use App\Infrastructure\Persistence\DBALTypes\JsonDocumentType;

final class TicketInListType extends JsonDocumentType
{
    public function getName(): string
    {
        return $this->className();
    }

    protected function className(): string
    {
        return TicketInList::class;
    }
}
