<?php

namespace App\Queries\Ticket\FindTicketById;

use App\Infrastructure\Persistence\DoctrineTypesInitializer\JsonDocumentType;

final class TicketByIdType extends JsonDocumentType
{
    public function getName(): string
    {
        return $this->className();
    }

    protected function className(): string
    {
        return TicketById::class;
    }
}
