<?php

namespace App\Product\Service;

use App\Common\Error;
use App\Product\Model\ProductId;
use App\Product\Service\Error\ProductEmailNotFound;
use Swift_Message;

final class TicketEmailFactory implements ProductEmailFactory
{
    private $findTicketEmail;

    private $from;

    public function __construct(FindTicketEmail $findTicketEmail, string $from)
    {
        $this->findTicketEmail = $findTicketEmail;
        $this->from            = $from;
    }

    public function create(ProductId $ticketId)
    {
        $ticketEmail = $this->findTicketEmail->find((string) $ticketId);
        if ($ticketEmail instanceof Error) {
            return new ProductEmailNotFound();
        }

        $message = (new Swift_Message())
            ->setSubject('Thanks for buy ticket')
            ->setFrom($this->from)
            ->setTo($ticketEmail->email)
            ->setBody(sprintf('ticket number is %s', $ticketEmail->number))
        ;

        return $message;
    }
}
