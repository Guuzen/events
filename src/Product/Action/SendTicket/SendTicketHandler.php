<?php

declare(strict_types=1);

namespace App\Product\Action\SendTicket;

use App\Product\Action\SendTicket\FindTicketEmail\FindTicketEmail;
use App\Product\Action\SendTicket\TicketSending\TicketSending;

final class SendTicketHandler
{
    private $ticketDelivery;

    private $findTicketEmail;

    public function __construct(TicketSending $ticketDelivery, FindTicketEmail $findTicketEmail)
    {
        $this->ticketDelivery  = $ticketDelivery;
        $this->findTicketEmail = $findTicketEmail;
    }

    public function handle(SendTicket $sendTicket): void
    {
        $ticketEmail = $this->findTicketEmail->find($sendTicket->ticketId);

        $this->ticketDelivery->send($ticketEmail);
    }
}
