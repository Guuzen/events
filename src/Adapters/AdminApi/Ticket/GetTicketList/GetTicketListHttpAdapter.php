<?php

declare(strict_types=1);

namespace App\Adapters\AdminApi\Ticket\GetTicketList;

use App\Infrastructure\Http\AppController\AppController;
use App\Infrastructure\Persistence\ResultSetMapping;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class GetTicketListHttpAdapter extends AppController
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Route("/admin/ticket/list", methods={"GET"})
     */
    public function __invoke(GetTicketListRequest $request): Response
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
                ticket.event_id = :event_id
            '
        );
        $stmt->bindValue('event_id', $request->eventId);
        $stmt->execute();

        /** @var array<int, array> $tickets */
        $tickets = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $mapping = ResultSetMapping::forStatement($stmt);
        $tickets = $mapping->mapKnownColumnsArray($this->connection->getDatabasePlatform(), $tickets);

        return $this->response($tickets);
    }
}
