<?php

declare(strict_types=1);

namespace App\Integrations\Email\SendTicket\TicketDelivery;

use App\Integrations\Email\SendTicket\FindTicketEmail\TicketEmail;
use Swift_Mailer;
use Swift_Message;
use function sprintf;

final class TicketDelivery
{
    /**
     * @var Swift_Mailer
     */
    private $mailer;

    /**
     * @var string
     */
    private $from;

    public function __construct(Swift_Mailer $mailer, string $from)
    {
        $this->mailer = $mailer;
        $this->from   = $from;
    }

    public function send(TicketEmail $ticketEmail): void
    {
        $email = (new Swift_Message())
            ->setSubject('Thanks for buy ticket')
            ->setFrom($this->from)
            ->setTo($ticketEmail->email)
            ->setBody(sprintf('ticket number is %s', $ticketEmail->number));

        $sent = $this->mailer->send($email);
        if (0 === $sent) {
            throw new TicketNotSent('');
        }
    }
}
