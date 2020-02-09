<?php

namespace App\Queries\Ticket\FindTicketById;

use App\Infrastructure\Http\RequestResolver\AppRequest;

/**
 * @psalm-immutable
 */
final class FindTicketById implements AppRequest
{
    public $ticketId;

    public function __construct(string $ticketId)
    {
        $this->ticketId = $ticketId;
    }
}
