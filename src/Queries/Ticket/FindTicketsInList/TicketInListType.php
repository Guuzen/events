<?php

namespace App\Queries\Ticket\FindTicketsInList;

use App\Common\JsonDocumentType;

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
