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
                row_to_json(ticket)
            from (
                select
                    *
                from
                    ticket
                where
                    ticket.id = :ticket_id
            ) as ticket
        ');
        $stmt->bindValue('ticket_id', $request->ticketId);
        $stmt->execute();

        /** @var string|false $ticketData */
        $ticketData = $stmt->fetchOne();
        if (false === $ticketData) {
            throw new TicketByIdNotFound('');
        }

        $decoded = $this->deserializeFromDb($ticketData);

        return $this->response($decoded);
    }
}
