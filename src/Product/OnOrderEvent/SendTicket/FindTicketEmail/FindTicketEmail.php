<?php

namespace App\Product\OnOrderEvent\SendTicket\FindTicketEmail;

use App\Product\Model\TicketId;
use Doctrine\DBAL\Connection;

// TODO rename GetTicketEmail ?
final class FindTicketEmail
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function find(TicketId $ticketId): TicketEmail
    {
        $stmt = $this->connection->prepare(
            '
            select
                "user".contacts ->> \'email\' as email,
                ticket.number
            from
                ticket
            left join
                "order" on ticket.order_id = "order".id
            left join
                "user" on "user".order_id = "order".id
            where ticket.id = :ticket_id
        '
        );
        $stmt->bindValue('ticket_id', (string)$ticketId);
        $stmt->execute();

        /** @var array{email: string, number: string}|false */
        $result = $stmt->fetchAssociative();

        if (false === $result) {
            throw new TicketEmailNotFound('');
        }

        return new TicketEmail($result['email'], $result['number']);
    }
}
