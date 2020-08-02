<?php

declare(strict_types=1);

namespace App\Product\Query\FindTicketById;

use App\Infrastructure\Http\AppController\AppController;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class FindTicketByIdHttpAdapter extends AppController
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Route("/admin/ticket/show", methods={"GET"})
     */
    public function __invoke(FindTicketByIdRequest $request): Response
    {
        $stmt = $this->connection->prepare('
            select
                ticket.id as id,
                ticket.event_id as "event_id",
                ticket.created_at as "created_at",
                ticket.number
            from
                ticket
            where
                ticket.id = :ticket_id
        ');
        $stmt->bindValue('ticket_id', $request->ticketId);
        $stmt->execute();

        /** @psalm-var array|false */
        $ticket = $stmt->fetch();
        if (false === $ticket) {
            return $this->response(new TicketByIdNotFound());
        }

        return $this->response($ticket);
    }
}
