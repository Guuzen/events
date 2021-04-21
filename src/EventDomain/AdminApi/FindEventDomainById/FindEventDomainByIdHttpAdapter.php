<?php

declare(strict_types=1);

namespace App\EventDomain\AdminApi\FindEventDomainById;

use App\Infrastructure\Http\AppController\AppController;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class FindEventDomainByIdHttpAdapter extends AppController
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @Route("/admin/eventDomain/{eventId}", methods={"GET"})
     */
    public function __invoke(FindEventDomainByIdRequest $request): Response
    {
        $stmt = $this->connection->prepare(
            '
            select
                *
            from
                event_domain
            where
                event_domain.id = :event_id
        '
        );
        $stmt->bindValue('event_id', $request->eventId);
        $stmt->execute();

        /** @var array|false */
        $event = $stmt->fetchAssociative();
        if (false === $event) {
            throw new EventDomainNotFound(''); // TODO exceptions in dev and in prod. In prod need only message. In dev - full trace?
        }

        return $this->validateResponse($event);
    }
}
