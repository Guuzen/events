<?php

declare(strict_types=1);

namespace App\Product\Action\SendTicket;

use App\Common\Error;
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

    public function handle(SendTicket $sendTicket): ?Error
    {
        $ticketEmail = $this->findTicketEmail->find($sendTicket->ticketId);
        if ($ticketEmail instanceof Error) {
            return $ticketEmail;
        }

        $error = $this->ticketDelivery->send($ticketEmail);
        if ($error instanceof Error) {
            return $error;
        }

        return null;
    }
}
