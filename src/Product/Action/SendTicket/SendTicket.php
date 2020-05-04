<?php

declare(strict_types=1);

namespace App\Product\Action\SendTicket;

use App\Product\Model\TicketId;

/**
 * @psalm-immutable
 *
 * TODO make all command's properties in terms of domain ?
 */
final class SendTicket
{
    public $ticketId;

    public function __construct(TicketId $ticketId)
    {
        $this->ticketId = $ticketId;
    }
}
