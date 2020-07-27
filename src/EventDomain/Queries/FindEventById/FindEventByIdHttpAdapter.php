<?php

declare(strict_types=1);

namespace App\EventDomain\Queries\FindEventById;

use App\Infrastructure\Http\AppController\AppController;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class FindEventByIdHttpAdapter extends AppController
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Route("/admin/eventDomain/show")
     */
    public function __invoke(FindEventByIdRequest $request): Response
    {
        $stmt = $this->connection->prepare('
            select
                *
            from
                event_domain
            where
                event_domain.id = :event_id
        ');
        $stmt->bindValue('event_id', $request->eventId);
        $stmt->execute();

        /** @var array|false */
        $event = $stmt->fetch();
        if (false === $event) {
            return $this->response(new EventNotFound());
        }

        return $this->response($event);
    }
}
