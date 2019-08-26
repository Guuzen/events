<?php

namespace App\Queries\Ticket;

use App\Common\AppRequest;

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
