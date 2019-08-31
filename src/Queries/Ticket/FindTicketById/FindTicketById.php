<?php

namespace App\Queries\Ticket\FindTicketById;

use App\Infrastructure\Http\AppRequest;

final class FindTicketById implements AppRequest
{
    /**
     * @readonly
     */
    public $ticketId;

    public function __construct(string $ticketId)
    {
        $this->ticketId = $ticketId;
    }
}
