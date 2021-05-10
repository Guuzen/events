<?php

declare(strict_types=1);

namespace App\Adapters\AdminApi\Event\FindEventDomainById;

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
        /** @var array|false $event */
        $event = $this->connection->fetchAssociative(
            '
            select
                *
            from
                event_domain
            where
                event_domain.id = :event_id
        ',
            ['event_id' => $request->eventId]
        );

        if (false === $event) {
            throw new EventDomainNotFound(''); // TODO exceptions in dev and in prod. In prod need only message. In dev - full trace?
        }

        return $this->response($event);
    }
}
