<?php

namespace App\Order\Action;

use Swift_Message;

final class SendTicketByEmail
{
    private $mailer;

    private $from;

    public function __construct(\Swift_Mailer $mailer, string $from)
    {
        $this->mailer = $mailer;
        $this->from   = $from;
    }

    /**
     * @param array{email: string, number: string} $data
     */
    public function send(array $data): void
    {
        $message = (new Swift_Message())
            ->setSubject('Thanks for buy ticket')
            ->setFrom($this->from)
            ->setTo($data['email'])
            ->setBody(sprintf('ticket number is %s', $data['number']))
        ;

        // TODO what if return 0 ?
        $this->mailer->send($message);
    }
}
