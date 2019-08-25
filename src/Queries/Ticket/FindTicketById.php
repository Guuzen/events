<?php

namespace App\Queries\Ticket;

use App\Common\AppRequest;

final class FindTicketById implements AppRequest
{
    public $ticketId;

    public function __construct(string $ticketId)
    {
        $this->ticketId = $ticketId;
    }
}
