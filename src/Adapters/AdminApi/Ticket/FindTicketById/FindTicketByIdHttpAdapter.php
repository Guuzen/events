<?php

declare(strict_types=1);

namespace App\Adapters\AdminApi\Ticket\FindTicketById;

use App\Infrastructure\Http\AppController\AppController;
use App\Infrastructure\Persistence\ResultSetMapping;
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
        /** @var \Doctrine\DBAL\Driver\PDO\Connection $pdo */
        $pdo = $this->connection->getWrappedConnection();

        $stmt = $pdo->prepare(
            '
            select
                *
            from
                ticket
            where
                ticket.id = :ticket_id
            '
        );
        $stmt->bindValue('ticket_id', $request->ticketId);
        $stmt->execute();

        /** @var array $ticket */
        $ticket = $stmt->fetch(\PDO::FETCH_ASSOC);

        $mapping = ResultSetMapping::forStatement($stmt);
        $ticket  = $mapping->mapKnownColumns($this->connection->getDatabasePlatform(), $ticket);

        return $this->response($ticket);
    }
}
