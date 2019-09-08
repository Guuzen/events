<?php

namespace App\Queries\Ticket;

use App\Queries\Ticket\FindTicketById\TicketById;
use App\Queries\Ticket\FindTicketById\TicketByIdNotFound;
use App\Queries\Ticket\FindTicketsInList\TicketInList;
use Doctrine\DBAL\Connection;

final class TicketQueries
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return TicketInList[]
     */
    public function findTicketsInList(string $eventId): array
    {
        $stmt = $this->connection->prepare('
            select
                row_to_json(ticket) as json
            from (
                select
                    ticket.id as id,
                    ticket.event_id as event_id,
                    tariff_details.tariff_type as type,
                    product.created_at as "created_at",
                    ticket.number,
                    product.reserved,
                    product.delivered_at
                from
                    product
                left join
                    ticket on ticket.id = product.id
                left join
                    tariff_details on tariff_details.id = product.tariff_id
                where
                    ticket.event_id = :event_id                 
            ) as ticket
        ');
        $stmt->bindValue('event_id', $eventId);
        $stmt->execute();

        $tickets = [];
        /** @psalm-var array{json: string} $ticketData */
        foreach ($stmt->fetchAll() as $ticketData) {
            /** @var TicketInList */
            $ticket    = $this->connection->convertToPHPValue($ticketData['json'], TicketInList::class);
            $tickets[] = $ticket;
        }

        return $tickets;
    }

    /**
     * @return TicketById|TicketByIdNotFound
     */
    public function findById(string $ticketId)
    {
        $stmt = $this->connection->prepare('
            select
                row_to_json(ticket) as json
            from (
                select
                    ticket.id as id,
                    ticket.event_id as event_id,
                    tariff_details.tariff_type as type,
                    product.created_at as "created_at",
                    ticket.number,
                    product.reserved,
                    product.delivered_at
                from
                    product
                left join
                    ticket on ticket.id = product.id
                left join
                    tariff_details on tariff_details.id = product.tariff_id
                where
                    ticket.id = :ticket_id
            ) as ticket
        ');
        $stmt->bindValue('ticket_id', $ticketId);
        $stmt->execute();

        /** @psalm-var array{json: string}|false */
        $ticketData = $stmt->fetch();
        if (false === $ticketData) {
            return new TicketByIdNotFound();
        }

        /** @var TicketById */
        $ticket = $this->connection->convertToPHPValue($ticketData['json'], TicketById::class);

        return $ticket;
    }
}
