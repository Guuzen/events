<?php

declare(strict_types=1);

namespace App\Product\Query\GetTicketList;

use App\Infrastructure\Http\AppController\AppController;
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
        $stmt = $this->connection->prepare('
            select
                json_agg(ticket)
            from (
                select
                    *
                from
                    ticket
                where
                    ticket.event_id = :event_id                 
            ) as ticket
        ');
        $stmt->bindValue('event_id', $request->eventId);
        $stmt->execute();

        /** @var string|false $ticketData */
        $ticketData = $stmt->fetchOne();
        if ($ticketData === false) {
            throw new TicketListNotFound('');
        }

        $decoded = $this->deserializeFromDb($ticketData);

        return $this->response($decoded);
    }
}
