<?php

namespace App\Infrastructure\Notifier;

use Swift_Message;

final class SendTicketToBuyerByEmailNotifier implements Notifier
{
    private $mailer;

    private $from;

    public function __construct(\Swift_Mailer $mailer, string $from)
    {
        $this->mailer = $mailer;
        $this->from   = $from;
    }

    public function notify(array $event): void
    {
        $message = (new Swift_Message())
            ->setSubject('Thanks for buy ticket')
            ->setFrom($this->from)
            ->setTo($event['email'])
            ->setBody(sprintf('ticket number is %s', $event['number']))
        ;

        // TODO what if return 0 ?
        $this->mailer->send($message);
    }
}
