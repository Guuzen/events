<?php

declare(strict_types=1);

namespace App\Integrations\Email;

use App\Integrations\Email\SendTicket\FindTicketEmail\FindTicketEmail;
use App\Integrations\Email\SendTicket\TicketDelivery\TicketDelivery;
use App\Model\Order\OrderMarkedPaid;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

final class OnOrderMarkedPaid implements MessageSubscriberInterface
{
    private $ticketDelivery;

    private $findTicketEmail;

    public function __construct(TicketDelivery $ticketDelivery, FindTicketEmail $findTicketEmail)
    {
        $this->ticketDelivery  = $ticketDelivery;
        $this->findTicketEmail = $findTicketEmail;
    }

    public static function getHandledMessages(): iterable
    {
        yield OrderMarkedPaid::class => [
            'method' => 'sendTicket',
        ];
    }

    public function sendTicket(OrderMarkedPaid $event): void
    {
        // TODO problems with this flow - sagas / bullshit subscribers priorities / change flow ?
        // TODO order marked paid ->
        // TODO 1. create ticket
        // TODO 2. send ticket (application side joins + retries with backoff if ticket still not created)
        //        try {
        $ticketEmail = $this->findTicketEmail->find($event->orderId);

        $this->ticketDelivery->send($ticketEmail);
    }
}