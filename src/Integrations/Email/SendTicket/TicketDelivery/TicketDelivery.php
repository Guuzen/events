<?php

declare(strict_types=1);

namespace App\Integrations\Email\SendTicket\TicketDelivery;

use App\Integrations\Email\TicketResource;
use Doctrine\DBAL\Connection;
use Guuzen\ResourceComposer\ResourceComposer;
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

    private $composer;

    private $connection;

    public function __construct(Swift_Mailer $mailer, string $from, ResourceComposer $composer, Connection $connection)
    {
        $this->mailer     = $mailer;
        $this->from       = $from;
        $this->composer   = $composer;
        $this->connection = $connection;
    }

    public function send(string $orderId): void
    {
        /** @var array $ticket */
        $ticket = $this->connection->fetchAssociative(
            'select * from ticket where ticket.order_id = :order_id',
            ['order_id' => $orderId]
        );

        /**
         * @var array{user: array{contacts: array{email: string}}, number: string} $ticket
         */
        $ticket = $this->composer->compose($ticket, TicketResource::class);

        $email = (new Swift_Message())
            ->setSubject('Thanks for buy ticket')
            ->setFrom($this->from)
            ->setTo($ticket['user']['contacts']['email'])
            ->setBody(sprintf('ticket number is %s', $ticket['number']));

        $sent = $this->mailer->send($email);
        if (0 === $sent) {
            throw new TicketNotSent('');
        }
    }
}
