<?php

namespace App\Product\Service;

use App\Product\Service\Error\TicketEmailNotFound;
use Doctrine\DBAL\Connection;

final class FindTicketEmail
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    // TODO delivery address in order ?

    /**
     * @return TicketEmail|TicketEmailNotFound
     */
    public function find(string $ticketId)
    {
        $stmt = $this->connection->prepare('
            select
                "user".contacts ->> \'email\' as email,
                ticket.number
            from
                "ticket"
            left join
                "order" on ticket.id = "order".product_id
            left join
                "user" on "user".id = "order".user_id
            where ticket.id = :ticket_id
        ');
        $stmt->bindValue('ticket_id', $ticketId);
        $stmt->execute();

        /** @var array{email: string, number: string}|false */
        $result = $stmt->fetch();

        if (false === $result) {
            return new TicketEmailNotFound();
        }

        return new TicketEmail($result['email'], $result['number']);
    }
}
