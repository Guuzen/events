<?php

namespace App\Queries\Ticket;

use Doctrine\DBAL\Connection;

final class TicketQueries
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findAll(string $eventId): array
    {
        $stmt = $this->connection->prepare('
            select
                ticket.id as id,
                ticket.event_id as event_id,
                product.type ->> \'type\' as type,
                product.created_at as "created_at",
                ticket.number,
                product.reserved
            from
                product
            left join
                ticket on ticket.id = product.id
            where
                ticket.event_id = :event_id
        ');
        $stmt->bindValue('event_id', $eventId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById(string $ticketId): array
    {
        $stmt = $this->connection->prepare('
            select
                ticket.id as id,
                ticket.event_id as event_id,
                product.type ->> \'type\' as type,
                product.created_at as "created_at",
                ticket.number,
                product.reserved
            from
                product
            left join
                ticket on ticket.id = product.id
            where
                ticket.id = :ticket_id
        ');
        $stmt->bindValue('ticket_id', $ticketId);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
