<?php

declare(strict_types=1);

namespace App\Integrations\Email;

use App\Integrations\Email\SendTicket\TicketDelivery\TicketDelivery;
use App\Model\TicketOrder\TicketOrderPaymentConfirmed;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

final class OnTicketOrderPaymentConfirmed implements MessageSubscriberInterface
{
    private $ticketDelivery;

    public function __construct(TicketDelivery $ticketDelivery)
    {
        $this->ticketDelivery = $ticketDelivery;
    }

    public static function getHandledMessages(): iterable
    {
        yield TicketOrderPaymentConfirmed::class => [
            'method' => 'sendTicket',
        ];
    }

    public function sendTicket(TicketOrderPaymentConfirmed $event): void
    {
        $this->ticketDelivery->send($event->orderId);
    }
}